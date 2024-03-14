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

    'accepted'             => 'Поле :attribute должно быть принято.',
    'accepted_if'          => 'Поле :attribute должно быть принято, когда :other равно :value.',
    'active_url'           => 'Поле :attribute должно быть допустимым URL.',
    'after'                => 'В поле :attribute должна быть дата после :date.',
    'after_or_equal'       => 'В поле :attribute должна быть дата, следующая за :date или равная :date.',
    'alpha'                => 'Поле :attribute должно содержать только буквы.',
    'alpha_dash'           => 'Поле :attribute должно содержать только буквы, цифры, тире и подчеркивания.',
    'alpha_num'            => 'Поле :attribute должно содержать только буквы и цифры.',
    'array'                => 'Поле :attribute должно быть массивом.',
    'ascii'                => 'Поле :attribute должно содержать только однобайтовые буквенно-цифровые символы и условные обозначения.',
    'before'               => 'В поле :attribute должна быть дата, предшествующая :date.',
    'before_or_equal'      => 'В поле :attribute должна быть дата, предшествующая :date или равная :date.',
    'between'              => [
        'array'   => 'В поле :attribute должно быть от :min до :max элементов.',
        'file'    => 'Поле :attribute должно быть от :min до :max килобайт.',
        'numeric' => 'Поле :attribute должно быть от :min до :max.',
        'string'  => 'Поле :attribute должно быть от :min до :max символов.',
    ],
    'boolean'              => 'Поле :attribute должно иметь значение true или false.',
    'can'                  => 'Поле :attribute содержит несанкционированное значение.',
    'confirmed'            => 'Подтверждение поля :attribute не соответствует.',
    'current_password'     => 'Пароль неверен.',
    'date'                 => 'В поле :attribute должна быть действительная дата.',
    'date_equals'          => 'В поле :attribute должна быть дата, равная :date.',
    'date_format'          => 'Поле :attribute должно соответствовать формату :format.',
    'decimal'              => 'Поле :attribute должно содержать :десятичные разряды после запятой.',
    'declined'             => 'Поле :attribute должно быть отклонено.',
    'declined_if'          => 'Поле :attribute должно быть отклонено, когда :other равно :value.',
    'different'            => 'Поле :attribute и :other должны отличаться.',
    'digits'               => 'Поле :attribute должно быть :digits цифры.',
    'digits_between'       => 'Поле :attribute должно содержать цифры от :min до :max.',
    'dimensions'           => 'Поле :attribute имеет недопустимые размеры изображения.',
    'distinct'             => 'Поле :attribute имеет повторяющееся значение.',
    'doesnt_end_with'      => 'Поле :attribute не должно заканчиваться одним из следующих значений :values.',
    'doesnt_start_with'    => 'Поле :attribute не должно начинаться с одного из следующих значений :values.',
    'email'                => 'Поле :attribute должно быть действительным адресом электронной почты.',
    'ends_with'            => 'Поле :attribute должно заканчиваться одним из следующих значений.',
    'enum'                 => 'Выбранный :attribute недействителен.',
    'exists'               => 'Выбранный :attribute недействителен.',
    'extensions'           => 'Поле :attribute должно иметь одно из следующих расширений :values.',
    'file'                 => 'Поле :attribute должно быть файлом.',
    'filled'               => 'Поле :attribute должно иметь значение.',
    'gt'                   => [
        'array'   => 'В поле :attribute должно быть больше, чем :value элементов.',
        'file'    => 'Поле :attribute должно быть больше, чем :value килобайт.',
        'numeric' => 'Поле :attribute должно быть больше, чем :value символов.',
        'string'  => 'Поле :attribute должно быть больше, чем :value символов.',
    ],
    'gte'                  => [
        'array'   => 'Поле :attribute должно содержать :elements значения или более.',
        'file'    => 'Поле :attribute должно быть больше или равно :size значения.',
        'numeric' => 'Поле :attribute должно быть больше или равно :value.',
        'string'  => 'Поле :attribute должно быть больше или равно :symbols значения.',
    ],
    'hex_color'            => 'Поле :attribute должно иметь допустимый шестнадцатеричный цвет.',
    'image'                => 'Поле :attribute должно быть изображением.',
    'in'                   => 'Выбранный :attribute недопустим.',
    'in_array'             => 'Поле :attribute должно существовать в :other.',
    'integer'              => 'Поле :attribute должно быть целым числом.',
    'ip'                   => 'Поле :attribute должно быть допустимым IP-адресом.',
    'ipv4'                 => 'Поле :attribute должно быть допустимым IPv4-адресом.',
    'ipv6'                 => 'Поле :attribute должно быть допустимым IPv6-адресом.',
    'json'                 => 'Поле :attribute должно быть допустимой строкой JSON.',
    'lowercase'            => 'Поле :attribute должно быть в нижнем регистре.',
    'lt'                   => [
        'array'   => 'В поле :attribute должно быть меньше, чем :value элементов.',
        'file'    => 'Поле :attribute должно быть меньше, чем :value килобайт.',
        'numeric' => 'Поле :attribute должно быть меньше, чем :value символов.',
        'string'  => 'Поле :attribute должно быть меньше, чем :value символов.',
    ],
    'lte'                  => [
        'array'   => 'Поле :attribute не должно содержать более элементов :value.',
        'file'    => 'Поле :attribute должно быть меньше или равно :value килобайт.',
        'numeric' => 'Поле :attribute должно быть меньше или равно :value.',
        'string'  => 'Поле :attribute должно быть меньше или равно :value символов.',
    ],
    'mac_address'          => 'Поле атрибута : должно быть действительным MAC-адресом.',
    'max'                  => [
        'array'   => 'Поле :attribute не должно содержать более :max элементов.',
        'file'    => 'Поле :attribute не должно превышать :max килобайт.',
        'numeric' => 'Поле :attribute не должно превышать :max.',
        'string'  => 'Поле :attribute не должно превышать :max символов.',
    ],
    'max_digits'           => 'Поле :attribute не должно содержать более :max цифр.',
    'mimes'                => 'Поле :attribute должно быть файлом типа :values.',
    'mimetypes'            => 'Поле :attribute должно быть файлом типа :values.',
    'min'                  => [
        'array'   => 'Поле :attribute должно содержать не менее :min элементов.',
        'file'    => 'Поле :attribute должно быть не менее :min килобайт.',
        'numeric' => 'Поле :attribute должно быть не менее :mine.',
        'string'  => 'Поле :attribute должно содержать не менее :min символов.',
    ],
    'min_digits'           => 'В поле :attribute должно быть не менее :min цифр.',
    'missing'              => 'Поле :attribute должно отсутствовать.',
    'missing_if'           => 'Поле :attribute должно отсутствовать, если :other равно :value.',
    'missing_unless'       => 'Поле :attribute должно отсутствовать, если :other не равно :value.',
    'missing_with'         => 'Поле :attribute должно отсутствовать, когда присутствует :values.',
    'missing_with_all'     => 'Поле :attribute должно отсутствовать, когда присутствуют :values.',
    'multiple_of'          => 'Поле :attribute должно быть кратным :value.',
    'not_in'               => 'Выбранный :attribute недопустим.',
    'not_regex'            => 'Формат поля :attribute недопустим.',
    'numeric'              => 'Поле атрибута : должно быть числом.',
    'password'             => [
        'letters'       => 'Поле :attribute должно содержать по крайней мере одну букву.',
        'mixed'         => 'Поле :attribute должно содержать по крайней мере одну заглавную и одну строчную букву.',
        'numbers'       => 'Поле :attribute должно содержать по крайней мере одно число.',
        'symbols'       => 'Поле :attribute должно содержать по крайней мере один символ.',
        'uncompromised' => 'Указанный :attribute появился в результате утечки данных. Пожалуйста, выберите другой атрибут :.',
    ],
    'present'              => 'Поле :attribute должно присутствовать.',
    'present_if'           => 'Поле :attribute должно присутствовать, когда :other равно :value.',
    'present_unless'       => 'Поле :attribute должно присутствовать, если :other не равно :value.',
    'present_with'         => 'Поле :attribute должно присутствовать, когда :values присутствует.',
    'present_with_all'     => 'Поле :attribute должно присутствовать, когда присутствуют :значения.',
    'prohibited'           => 'Поле :attribute запрещено.',
    'prohibited_if'        => 'Поле :attribute запрещено, когда :other равно :value.',
    'prohibited_unless'    => 'Поле :attribute запрещено, если :other не находится в :values.',
    'prohibits'            => 'Поле :attribute запрещает присутствие :other.',
    'regex'                => 'Недопустимый формат поля :attribute.',
    'required'             => 'Поле :attribute является обязательным.',
    'required_array_keys'  => 'Поле :attribute должно содержать записи для: :values.',
    'required_if'          => 'Поле :attribute требуется, когда :other является :value.',
    'required_if_accepted' => 'Поле :attribute требуется, когда :other принимается.',
    'required_unless'      => 'Поле :attribute требуется, если :other не находится в :values.',
    'required_with'        => 'Поле :attribute требуется, когда присутствует :values.',
    'required_with_all'    => 'Поле :attribute требуется, когда присутствуют :values.',
    'required_without'     => 'Поле :attribute требуется, когда :values отсутствует.',
    'required_without_all' => 'Поле :attribute требуется, когда ни одно из :values не присутствует.',
    'same'                 => 'Поле :attribute должно соответствовать :other.',
    'size'                 => [
        'array'   => 'Поле :attribute должно содержать :элементы размера.',
        'file'    => 'Поле :attribute должно быть :size в килобайтах.',
        'numeric' => 'Поле :attribute должно быть :size.',
        'string'  => 'Поле :attribute должно быть :size символов.',
    ],
    'starts_with'          => 'Поле :attribute должно начинаться с одного из следующих значений :values.',
    'string'               => 'Поле :attribute должно быть строкой.',
    'timezone'             => 'Поле :attribute должно быть допустимым часовым поясом.',
    'unique'               => ':attribute уже выбран.',
    'uploaded'             => ':attribute не удалось загрузить.',
    'uppercase'            => 'Поле :attribute должно быть прописным.',
    'url'                  => 'Поле :attribute должно быть допустимым URL.',
    'ulid'                 => 'Поле :attribute должно быть допустимым UUID.',
    'uuid'                 => 'Поле :attribute должно быть допустимым UUID.',

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
