<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => "L'attribut : doit être accepté..",
    'accepted_if' => "L'attribut :attribute doit être accepté lorsque :other vaut :value.",
    'active_url' => "L'attribut :attribute n'est pas une URL valide.",
    'after' => "L'attribut :attribute doit être une date après :date.",
    'after_or_equal' => "L'attribut :attribute doit être une date après ou égale à :date.",
    'alpha' => "L'attribut :attribute doit uniquement contenir des lettres.",
    'alpha_dash' => "L'attribut :attribute doit uniquement contenir des lettres, nombres, points et underscores.",
    'alpha_num' => "L'attribut :attribute doit uniquement contenir des caractères alphanumériques.",
    'array' => "L'attribut :attribute doit être un tableau.",
    'before' => "L'attribut :attribute doit être une date avant :date.",
    'before_or_equal' => "L'attribut :attribute doit être une date avant ou égale à :date.",
    'between' => [
        'array' => "L'attribut :attribute doit avoir entre :min et :max objets.",
        'file' => "L'attribut :attribute doit faire entre :min et :max kilobytes.",
        'numeric' => "L'attribut :attribute doit être entre :min et :max.",
        'string' => "L'attribut :attribute doit comporter entre :min et :max caractères.",
    ],
    'boolean' => "L'attribut :attribute doit être vrai ou faux",
    'confirmed' => "L'attribut :attribute de confirmation ne correspond pas.",
    'current_password' => 'Le mot de passe est incorrect.',
    'date' => "L'attribut :attribute n'est pas une date valide",
    'date_equals' => "L'attribut :attribute doit être égal à :date.",
    'date_format' => "L'attribut :attribute necorrespond pas au format :format.",
    'declined' => "L'attribut :attribute doit être moindre",
    'declined_if' => "L'attribut :attribute doit être moindre lorsque :other est :value.",
    'different' => "L'attribut :attribute et :other doit être différent.",
    'digits' => "L'attribut :attribute doit être :digits digits.",
    'digits_between' => "L'attribut :attribute doit être entre :min et :max digits.",
    'dimensions' => "L'attribut :attribute a des dimensions d'image invalides",
    'distinct' => "L'attribut :attribute a une valeur duppliquée.",
    'doesnt_start_with' => "L'attribut :attribute peut ne pas commencer par l'un des éléments suivants: :values.",
    'email' => "L'attribut :attribute doit être une adresse e-mail valide.",
    'ends_with' => "L'attribut :attribute doit se terminer par l'un des éléments suivants: :values.",
    'enum' => "L'attribut sélectionné :attribute est invalide.",
    'exists' => "L'attribut sélectionné :attribute est invalide.",
    'file' => "L'attribut :attribute doit être un fichier.",
    'filled' => "L'attribut :attribute field doit avoir une valeur.",
    'gt' => [
        'array' => "L'attribut :attribute doit avoir plus de :value objets.",
        'file' => "L'attribut :attribute doit être plus élevé que :value kilobytes.",
        'numeric' => "L'attribut :attribute doit être plus élevé que :value.",
        'string' => "L'attribut :attribute doit être plus élevé que :value characters.",
    ],
    'gte' => [
        'array' => "L'attribut :attribute must have :value objets or more.",
        'file' => "L'attribut :attribute doit être plus élevé ou égal à :value kilobytes.",
        'numeric' => "L'attribut :attribute doit être plus élevé ou égal à :value.",
        'string' => "L'attribut :attribute doit être plus élevé ou égal à :value characters.",
    ],
    'image' => "L'attribut :attribute doit être une image.",
    'in' => "L'attribut sélectionné :attribute est invalide.",
    'in_array' => "L'attribut :attribute field dn'existe pas dans :other.",
    'integer' => "L'attribut :attribute doit être un nombre entier.",
    'ip' => "L'attribut :attribute doit être une adresse Ip valide.",
    'ipv4' => "L'attribut :attribute oit être une adresse Ip IPv4 valide.",
    'ipv6' => "L'attribut :attribute oit être une adresse Ip IPv6 valide.",
    'json' => "L'attribut :attribute doit être une chaine JSON valide.",
    'lt' => [
        'array' => "L'attribut :attribute doit avoir moins de :value objets.",
        'file' => "L'attribut :attribute doit être à moins de :value kilobytes.",
        'numeric' => "L'attribut :attribute oit être à moins de :value.",
        'string' => "L'attribut :attribute oit être à moins de :value caractères.",
    ],
    'lte' => [
        'array' => "L'attribut :attribute ne doit pas avoir plus de  :value objets.",
        'file' => "L'attribut :attribute doit être à moins ou égal à :value kilobytes.",
        'numeric' => "L'attribut :attribute doit être à moins ou égal à :value.",
        'string' => "L'attribut :attribute doit être à moins ou égal à :value caractères.",
    ],
    'mac_address' => "L'attribut :attribute doit être une adresse MAC valide.",
    'max' => [
        'array' => "L'attribut :attribute ne doit avoir plus de :max objets.",
        'file' => "L'attribut :attribute ne doit pas être plus élevé que :max kilobytes.",
        'numeric' => "L'attribut :attribute ne doit pas être plus élevé que :max.",
        'string' => "L'attribut :attribute ne doit pas être plus élevé que :max caractères.",
    ],
    'mimes' => "L'attribut :attribute doit être un fichier de type: :values.",
    'mimetypes' => "L'attribut :attribute doit être un fichier de type: :values.",
    'min' => [
        'array' => "L'attribut :attribute doit avoir au moins :min objets.",
        'file' => "L'attribut :attribute doit faire au moins :min kilobytes.",
        'numeric' => "L'attribut :attribute doit être à au moins :min.",
        'string' => "L'attribut :attribute doit être à au moins :min characters.",
    ],
    'multiple_of' => "L'attribut :attribute doit être un multiple de :value.",
    'not_in' => "L'attribut sélectionné :attribute est invalide.",
    'not_regex' => "L'attribut :attribute est à un format invalide.",
    'numeric' => "L'attribut :attribute doit être un nombre.",
    'password' => [
        'letters' => "L'attribut :attribute doit contenir au moins une lettre.",
        'mixed' => "L'attribut :attribute doit contenir au moins une minucule et une majuscule.",
        'numbers' => "L'attribut :attribute doit contenir au moins un chiffre.",
        'symbols' => "L'attribut :attribute doit contenir au moins un symbole",
        'uncompromised' => "L'attribut' :attribute est apparue dans une fuite de données. Veuillez choisir un autre :attribute.",
    ],
    'present' => "L'attribut :attribute doit être présent.",
    'prohibited' => "L'attribut :attribute est interdit.",
    'prohibited_if' => "L'attribut :attribute field est interdit lorsque :other est :value.",
    'prohibited_unless' => "L'attribut :attribute est interdit à moins que :other ne soit in :values.",
    'prohibits' => "L'attribut :attribute interdit :other d'être présent",
    'regex' => "L'attribut :attribute a un format invalide.",
    'required' => "L'attribut :attribute field est obligatoire.",
    'required_array_keys' => "L'attribut :attribute doit contenir des entrées pour: :values.",
    'required_if' => "L'attribut :attribute est obligatoire lorsque :other est :value.",
    'required_unless' => "L'attribut :attribute est obligatoire à moins que :other ne soit in :values.",
    'required_with' => "L'attribut :attribute est obligatoire lorsque :values est present.",
    'required_with_all' => "L'attribut :attribute est obligatoire lorsque :values sont presents.",
    'required_without' => "L'attribut :attribute est obligatoire lorsque :values n'est pas present.",
    'required_without_all' => "L'attribut :attribute est obligatoire lorsque aucun des :values ne sont presents.",
    'same' => "L'attribut :attribute et :other doivent correspondre.",
    'size' => [
        'array' => "L'attribut :attribute doit contenir :size objets.",
        'file' => "L'attribut :attribute doit faire :size kilobytes.",
        'numeric' => "L'attribut :attribute doit faire :size.",
        'string' => "L'attribut :attribute doit faire :size caractères.",
    ],
    'starts_with' => "L'attribut :attribute doit commencer par l'un des éléments suivants: :values.",
    'string' => "L'attribut :attribute doit être une chaine de caractères.",
    'timezone' => "L'attribut :attribute doit être un fuseau horaire valide.",
    'unique' => "L'attribut :attribute a déjà été pris.",
    'uploaded' => "L'attribut :attribute a échoué au téléchargement.",
    'url' => "L'attribut :attribute doit être une URL valide.",
    'uuid' => "L'attribut :attribute doit être une UUID valide.",

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [],

];
