<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ImageTransformation;
use App\Http\Controllers\Controller;
use App\Http\Requests\QuestionRequest;
use App\Http\Requests\Questions\QuestionIndexRequest;
use App\Http\Requests\Questions\QuestionSearchRequest;
use App\Http\Requests\Questions\QuestionStoreRequest;
use App\Http\Requests\Questions\QuestionVoteRequest;
use App\Http\Resources\QuestionIndexResource;
use App\Http\Resources\Questions\QuestionSearchResource;
use App\Http\Resources\Questions\QuestionShowResource;
use App\Http\Resources\Questions\QuestionVoteResource;
use App\Models\Question;
use App\Models\QuestionChoice;
use App\Models\QuestionTag;
use App\Models\QuestionVote;
use App\Models\Tag;
use App\Models\User;
use App\Services\ElasticService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Throwable;
use function imageavif;

class QuestionController extends Controller
{

    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Ajout de middleware nécessaire selon les actions en en excluant certains
        $this->middleware('auth:sanctum')->except(['index', 'show', 'search']);
    }

    /**
     * Affiche la liste des questions
     * /api/questions
     *
     * @param QuestionIndexRequest $request
     * @return AnonymousResourceCollection
     */
    public function index(QuestionIndexRequest $request): AnonymousResourceCollection
    {

        // Si pas de recherche
        if (empty($request->search)) {
            return QuestionIndexResource::collection(Question::where('is_moderated', true)->where('is_integrated', null)->orderBy('created_at', 'DESC')->paginate(20));
        } // Si on a une recherche selon un tag / thème
        else if ($request->searchMod == 0) {
            // ID du tag correspondant à la recherche
            $tag = Tag::where('name', $request->search)->first();
            if (!empty($tag->id)) {
                // Récupération de toutes les questions ayant un rapport avec le thème recherché
                $questionTags = QuestionTag::where('tag_id', $tag->id)->orderBy('question_id', 'ASC')->paginate(20);
                $questions = [];
                // Construction du tableau de réponses
                foreach ($questionTags as $questionTag) {
                    $questions[] = $questionTag->question;
                }
                return QuestionIndexResource::collection($questions);
            } else {
                return QuestionIndexResource::collection([]);
            }
        } // Si on a une recherche selon une question
        else {
            return QuestionIndexResource::collection(Question::where('question', 'like', "%$request->search%")->paginate(20));
        }
    }


    /**
     * @param QuestionSearchRequest $request
     * @param ElasticService $elasticService
     * @return JsonResponse|QuestionSearchResource
     */
    public function search(QuestionSearchRequest $request, ElasticService $elasticService): JsonResponse|QuestionSearchResource
    {
        try {
            // Connexion au serveur Elastic pour récupérer les 5 résultats les plus probables
            $response = $elasticService->getMatchFromIndex('questions', 5, 'question', $request->question);
            // Si on obtient bien des résultats
            if ($response->ok()) {
                return new QuestionSearchResource($response->json());
            } else {
                return response()->json(['message' => "Erreur dans l'accès aux questions'"], 404);
            }
        } catch (Exception) {
            return response()->json(['message' => "Erreur dans la requête"], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param QuestionStoreRequest $request
     * @return JsonResponse
     */
    public function store(QuestionStoreRequest $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            // Création de la question
            $question = Question::create([
                'question' => $request->question,
                'user_id' => auth()->id()
            ]);
            // Ajout des choix pour la question
            foreach ($request->choices as $choiceK => $choiceV) {
                QuestionChoice::create([
                    'question_id' => $question->id,
                    'is_correct' => $choiceK == 0,
                    'title' => $choiceV,
                ]);
            }
            // Association des thèmes pour la question
            foreach ($request->selectedThemes as $theme) {
                $tag = Tag::whereName($theme)->first();
                QuestionTag::create([
                    'question_id' => $question->id,
                    'tag_id' => $tag->id,
                ]);
            }

            //? Si on a une image valide
            if ($request->imageNeeded && $request->image && function_exists('imageavif')) {
                // Définition du nom de l'image
                $question->image = $question->id . '.avif';
                // Propriétés de l'image
                $imgProperties = getimagesize($request->image->path());
                // Selon le type de l'image
                switch ($request->image->extension()) {
                    case 'jpg':
                        $gdImage = imagecreatefromjpeg($request->image->path());
                        $resizeBigImg = ImageTransformation::image_resize_big($gdImage, $imgProperties[0], $imgProperties[1]);
                        imageavif($resizeBigImg, storage_path('app/public/img/questions/big/' . $question->image));
                        // Création d'une miniature
                        $resizeSmallImg = ImageTransformation::image_resize_small($gdImage, $imgProperties[0], $imgProperties[1]);
                        break;
                    case 'jpeg':
                        $gdImage = imagecreatefromjpeg($request->image->path());
                        $resizeBigImg = ImageTransformation::image_resize_big($gdImage, $imgProperties[0], $imgProperties[1]);
                        imageavif($resizeBigImg, storage_path('app/public/img/questions/big/' . $question->image));
                        $resizeSmallImg = ImageTransformation::image_resize_small($gdImage, $imgProperties[0], $imgProperties[1]);
                        break;
                    case 'png':
                        $gdImage = imagecreatefrompng($request->image->path());
                        $resizeBigImg = ImageTransformation::image_resize_big($gdImage, $imgProperties[0], $imgProperties[1]);
                        imageavif($resizeBigImg, storage_path('app/public/img/questions/big/' . $question->image));
                        $resizeSmallImg = ImageTransformation::image_resize_small($gdImage, $imgProperties[0], $imgProperties[1]);
                        break;
                    case 'avif':
                        $gdImage = imagecreatefromavif($request->image->path());
                        $resizeBigImg = ImageTransformation::image_resize_big($gdImage, imagesx($gdImage), imagesy($gdImage));
                        imageavif($resizeBigImg, storage_path('app/public/img/questions/big/' . $question->image));
                        $resizeSmallImg = ImageTransformation::image_resize_small($gdImage, imagesx($gdImage), imagesy($gdImage));
                        break;
                    default:
                        $gdImage = imagecreatefromjpeg($request->image->path());
                        $resizeBigImg = ImageTransformation::image_resize_big($gdImage, $imgProperties[0], $imgProperties[1]);
                        imageavif($resizeBigImg, storage_path('app/public/img/questions/big/' . $question->image));
                        $resizeSmallImg = ImageTransformation::image_resize_small($gdImage, $imgProperties[0], $imgProperties[1]);
                        break;
                }
                imageavif($resizeSmallImg, storage_path('app/public/img/questions/small/' . $question->image));
                imagedestroy($gdImage);
                imagedestroy($resizeBigImg);
                imagedestroy($resizeSmallImg);
                $question->save();
            }

            // Validation de la transaction
            DB::commit();
        } // Si erreur dans la transaction
        catch (Exception $e) {
            DB::rollback();
            return response()->json(['success' => false, 'message' => $e->getMessage() . ' >>> Ligne ' . $e->getLine(),], 500);
        }

        return response()->json(['success' => true, 'message' => "Votre question a été proposée!"]);
    }

    /**
     * Display the specified resource.
     *
     * @param Question $question
     * @return QuestionShowResource
     */
    public function show(Question $question): QuestionShowResource
    {
        return new QuestionShowResource($question);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param QuestionRequest $request
     * @param Question $question
     * @return QuestionIndexResource
     */
    public function update(QuestionRequest $request, Question $question): QuestionIndexResource
    {
        $question->update($request->validated());

        return new QuestionIndexResource($question);
    }

    /**
     * Voter pour ou contre une question
     *
     * @param QuestionVoteRequest $request
     * @param Question $question
     * @return QuestionVoteResource|JsonResponse
     */
    public function vote(QuestionVoteRequest $request, Question $question): QuestionVoteResource|JsonResponse
    {
        DB::beginTransaction();
        try {
            // Mise en place du vote, s'il existe déjà une ligne avec cet utilisateur et cette question, update, sinon create
            QuestionVote::updateOrCreate(
                ['user_id' => auth()->id(), 'question_id' => $question->id],
                ['has_approved' => $request->ispositive]
            );

            // Quel est le nouveau compte de votes de vote désormais ?
            // Votes positifs
            $positiveVote = $question->hasMany(QuestionVote::class)->where('has_approved', true)->count();
            // Votes négatifs
            $negativeVote = $question->hasMany(QuestionVote::class)->where('has_approved', false)->count();

            $totalUsers = User::where('is_banned', false)->count();
            // Quelle est la valeur de vote pour une question ? Pour arriver à 100 il faudrait qu'au moins un dixième des utilisateurs mettent oui
            $voteRatio = ceil(100 / ($totalUsers / 10));
            // Si le pouvoir de vote selon le total d'utilisateur est inférieur à 1, on laisse 1
            $voteRatio = max($voteRatio, 1);

            // Soustraction des deux
            $voteScore = ($voteRatio * $positiveVote) - ($voteRatio * $negativeVote);
            // Mise à jour du score de la question
            $question->vote = $voteScore;
            $question->save();
            $data = [
                'ispositive' => $request->ispositive,
                'questionid' => $request->questionid,
                'voteScore' => $voteScore
            ];
            DB::commit();

        } catch (Throwable $th) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $th->getMessage()]) ;
        }
        // Retour dans le front des informations
        return new QuestionVoteResource($data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Question $question
     * @return Response
     */
    public function destroy(Question $question): Response
    {
        $question->delete();

        return response()->noContent();
    }
}
