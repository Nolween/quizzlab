<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\QuestionRequest;
use App\Http\Requests\Questions\QuestionIndexRequest;
use App\Http\Requests\Questions\QuestionSearchRequest;
use App\Http\Requests\Questions\QuestionVoteRequest;
use App\Http\Resources\QuestionIndexResource;
use App\Http\Resources\Questions\QuestionSearchResource;
use App\Http\Resources\Questions\QuestionShowResource;
use App\Http\Resources\Questions\QuestionVoteResource;
use App\Models\Question;
use App\Models\QuestionTag;
use App\Models\QuestionVote;
use App\Models\Tag;
use App\Services\ElasticService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuestionController extends Controller
{

    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Ajout des middleware nécessaire selon les actions en en exluant certains
        $this->middleware('auth:sanctum')->except(['index', 'show', 'search']);
    }

    /**
     * Affiche la liste des questions
     * /api/questions
     *
     * @return \Illuminate\Http\Response
     */
    public function index(QuestionIndexRequest $request, ElasticService $elasticService)
    {

        // Si pas de recherche
        if (empty($request->search)) {
            return QuestionIndexResource::collection(Question::paginate(20));
        }
        // Si on a une recherche selon un tag / thème
        else if ($request->searchMod == 0) {
            // ID du tag correpsondant à la recherche 
            $tag = Tag::where('name', $request->search)->first();
            if (!empty($tag->id)) {
                // Récupération de tous les questions ayant un rapport avec le thème recherché 
                $questionTags = QuestionTag::where('tag_id', $tag->id)->orderBy('question_id', 'ASC')->paginate(20);
                $questions = [];
                // Construcion du tableau de réponses
                foreach ($questionTags as $questionTags) {
                    $questions[] = $questionTags->question;
                }
                return QuestionIndexResource::collection($questions);
            } else {
                return QuestionIndexResource::collection([]);
            }
        }
        // Si on a une recherche selon une question
        else if ($request->searchMod == 1) {
            return QuestionIndexResource::collection(Question::where('question', 'like', "%$request->search%")->paginate(20));
        }
    }

    public function search(QuestionSearchRequest $request, ElasticService $elasticService)
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
        } catch (Exception $e) {
            return response()->json(['message' => "Erreur dans la requete"], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\QuestionRequest  $request
     * @return \App\Http\Resources\QuestionIndexResource
     */
    public function store(QuestionRequest $request)
    {
        $question = Question::create($request->validated());

        return new QuestionIndexResource($question);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Question  $question
     * @return \App\Http\Resources\QuestionShowResource
     */
    public function show(Question $question)
    {
        return new QuestionShowResource($question);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\QuestionRequest  $request
     * @param  \App\Models\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function update(QuestionRequest $request, Question $question)
    {
        $question->update($request->validated());

        return new QuestionIndexResource($question);
    }

    /**
     * Voter pour ou contre une question
     *
     * @param  \App\Http\Requests\Questions\QuestionVoteRequest  $request
     * @param  \App\Models\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function vote(QuestionVoteRequest $request, Question $question)
    {
        $user = Auth::user();
        // Mise en place du vote, si il existe déjà une ligne avec cet utilisateur et cette question, update, sinon create
        $questionVote = QuestionVote::updateOrCreate(
            ['user_id' => $user->id, 'question_id' => $question->id],
            ['has_approved' => $request->ispositive]
        );

        // Retour dans le front des informations
        return new QuestionVoteResource($request);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function destroy(Question $question)
    {
        $question->delete();

        return response()->noContent();
    }
}
