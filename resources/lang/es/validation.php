<?php

return [

	/*
	|--------------------------------------------------------------------------
	| Validación del idioma
	|--------------------------------------------------------------------------
	|
        | Las siguientes líneas de idioma contienen los mensajes de error predeterminados utilizados por
        | La clase validadora. Algunas de estas reglas tienen múltiples versiones tales
        | como las reglas de tamaño. Siéntase libre de modificar cada uno de estos mensajes aquí.
	|
	*/


	'accepted'              => 'El campo :attribute debe ser aceptado.',
	'active_url'            => 'El campo :attribute no es una URL válida.',
	'after'                 => 'El campo :attribute debe ser una fecha después de :date.',
	'after_or_equal'        => 'El campo :attribute debe ser una fecha después o igual a :date.',
	'alpha'                 => 'El campo :attribute sólo puede contener letras.',
	'alpha_dash'            => 'El campo :attribute sólo puede contener letras, números y guiones.',
	'alpha_num'             => 'El campo :attribute sólo puede contener letras y números.',
	'array'                 => 'El campo :attribute debe ser un arreglo.',
	'before'                => 'El campo :attribute debe ser una fecha antes de :date.',
	'before_or_equal'       => 'El campo :attribute debe ser una fecha antes o igual a :date.',
	'between'               => [
		'numeric' => 'El campo :attribute debe estar entre :min - :max.',
		'file'    => 'El campo :attribute debe estar entre :min - :max kilobytes.',
		'string'  => 'El campo :attribute debe estar entre :min - :max caracteres.',
		'array'   => 'El campo :attribute debe tener entre :min y :max elementos.',
	],
	'boolean'               => 'El campo :attribute debe ser verdadero o falso.',
	'confirmed'             => 'El campo de confirmación de :attribute no coincide.',
	'date'                  => 'El campo :attribute no es una fecha válida.',
	'date_format' 	        => 'El campo :attribute no corresponde con el formato :format.',
	'different'             => 'Los campos :attribute y :other deben ser diferentes.',
	'digits'                => 'El campo :attribute debe ser de :digits dígitos.',
	'digits_between'        => 'El campo :attribute debe tener entre :min y :max dígitos.',
	'dimensions'            => 'El campo :attribute no tiene una dimensión válida.',
	'distinct'              => 'El campo :attribute tiene un valor duplicado.',
	'email'                 => 'El formato del :attribute es inválido.',
	'exists'                => 'El campo :attribute seleccionado es inválido.',
	'file'                  => 'El campo :attribute debe ser un archivo.',
	'filled'                => 'El campo :attribute es requerido.',
	'gt'                    => [
		'numeric' => 'El campo :attribute debe ser mayor a :value.',
		'file'    => 'El campo :attribute debe ser mayor a :value kilobytes.',
		'string'  => 'El campo :attribute debe ser mayor a :value caracteres.',
		'array'   => 'El campo :attribute puede tener hasta :value elementos.',
	],
	'gte'                   => [
		'numeric' => 'El campo :attribute debe ser mayor o igual a :value.',
		'file'    => 'El campo :attribute debe ser mayor o igual a :value kilobytes.',
		'string'  => 'El campo :attribute debe ser mayor o igual a :value caracteres.',
		'array'   => 'El campo :attribute puede tener :value elementos o más.',
	],
	'image'                 => 'El campo :attribute debe ser una imagen.',
	'in'                    => 'El campo :attribute seleccionado es inválido.',
	'in_array'              => 'El campo :attribute no existe en :other.',
	'integer'               => 'El campo :attribute debe ser un entero.',
	'ip'                    => 'El campo :attribute debe ser una dirección IP válida.',
	'ipv4'                  => 'El campo :attribute debe ser una dirección IPv4 válida.',
	'ipv6'                  => 'El campo :attribute debe ser una dirección IPv6 válida.',
	'json'                  => 'El campo :attribute debe ser una cadena JSON válida.',
	'lt'                   => [
		'numeric' => 'El campo :attribute debe ser menor a :max.',
		'file'    => 'El campo :attribute debe ser menor a :max kilobytes.',
		'string'  => 'El campo :attribute debe ser menor a :max caracteres.',
		'array'   => 'El campo :attribute puede tener hasta :max elementos.',
	],
	'lte'                   => [
		'numeric' => 'El campo :attribute debe ser menor o igual a :max.',
		'file'    => 'El campo :attribute debe ser menor o igual a :max kilobytes.',
		'string'  => 'El campo :attribute debe ser menor o igual a :max caracteres.',
		'array'   => 'El campo :attribute no puede tener más que :max elementos.',
	],
	'max'                   => [
		'numeric' => 'El campo :attribute debe ser menor a :max.',
		'file'    => 'El campo :attribute debe ser menor a :max kilobytes.',
		'string'  => 'El campo :attribute debe ser menor a :max caracteres.',
		'array'   => 'El campo :attribute puede tener hasta :max elementos.',
	],
	'mimes'                 => 'El campo :attribute debe ser un archivo de tipo: :values.',
	'mimetypes'             => 'El campo :attribute debe ser un archivo de tipo: :values.',
	'min'                   => [
		'numeric' => 'El campo :attribute debe tener al menos :min.',
		'file'    => 'El campo :attribute debe tener al menos :min kilobytes.',
		'string'  => 'El campo :attribute debe tener al menos :min caracteres.',
		'array'   => 'El campo :attribute debe tener al menos :min elementos.',
	],
	'not_in'                => 'El campo :attribute seleccionado es invalido.',
	'not_regex'             => 'El formato del campo :attribute es inválido.',
	'numeric'               => 'El campo :attribute debe ser un número.',
	'present'               => 'El campo :attribute debe estar presente.',
	'regex'                 => 'El formato del campo :attribute es inválido.',
	'required'              => 'El campo :attribute es requerido.',
	'required_if'           => 'El campo :attribute es requerido cuando el campo :other es :value.',
	'required_unless'       => 'El campo :attribute es requerido a menos que :other esté presente en :values.',
	'required_with'         => 'El campo :attribute es requerido cuando :values está presente.',
	'required_with_all'     => 'El campo :attribute es requerido cuando :values está presente.',
	'required_without'      => 'El campo :attribute es requerido cuando :values no está presente.',
	'required_without_all'  => 'El campo :attribute es requerido cuando ningún :values está presente.',
	'same'                  => 'El campo :attribute y :other debe coincidir.',
	'size'                  => [
		'numeric' => 'El campo :attribute debe ser :size.',
		'file'    => 'El campo :attribute debe tener :size kilobytes.',
		'string'  => 'El campo :attribute debe tener :size caracteres.',
		'array'   => 'El campo :attribute debe contener :size elementos.',
	],
	'starts_with'           => 'El :attribute debe empezar con uno de los siguientes valores :values',
	'string'                => 'El campo :attribute debe ser una cadena.',
	'timezone'              => 'El campo :attribute debe ser una zona válida.',
	'unique'                => 'El campo :attribute ya ha sido tomado.',
	'uploaded'              => 'El campo :attribute no ha podido ser cargado.',
	'url'                   => 'El formato de :attribute es inválido.',
	'uuid'                  => 'El :attribute debe ser un UUID valido.',

	/*
	|--------------------------------------------------------------------------
	| Validación del idioma personalizado
	|--------------------------------------------------------------------------
	|
	|	Aquí puede especificar mensajes de validación personalizados para atributos utilizando el
	| convención "attribute.rule" para nombrar las líneas. Esto hace que sea rápido
	| especifique una línea de idioma personalizada específica para una regla de atributo dada.
	|
	*/

	'custom' => [
		'attribute-name' => [
			'rule-name'  => 'custom-message',
		],
	],

	/*
	|--------------------------------------------------------------------------
	| Atributos de validación personalizados
	|--------------------------------------------------------------------------
	|
        | Las siguientes líneas de idioma se utilizan para intercambiar los marcadores de posición de atributo.
        | con algo más fácil de leer, como la dirección de correo electrónico.
        | de "email". Esto simplemente nos ayuda a hacer los mensajes un poco más limpios.
	|
	*/

	'attributes' => [
		'region_id' 			=> 'región',
		'director_regional_id' 	=> 'director(a) regional',
		'codigo' 				=> 'código',
		'subdirector_id' 		=> 'subdirector',
		'centro_formacion_id'  	=> 'centro de formación',
		'sector_productivo_id'  => 'sector productivo',
		'mesa_tecnica_id' 		=> 'mesa técnica',
		'descripcion'           => 'descripción',
		'linea_programatica_id' => 'línea programática',
		'categoria'				=> 'categoría',
		'acronimo' 				=> 'acrónimo',
		'enlace_gruplac' 		=> 'enlace GrupLAC',
		'codigo_minciencias'    => 'código Minciencias',
		'categoria_minciencias' => 'categoría Minciencias',
		'year' 					=> 'año',
		'tipo_proyecto_id' 		=> 'tipo de proyecto',
        'actividad_economica_id'=> 'actividad económica',
		'linea_tecnologica_id'  => 'línea tecnológica',
		'titulo' 				=> 'título',
		'mesa_sectorial_id' 	=> 'mesa sectorial',
		'muestreo' 				=> '¿Cuál es el origen de las muestras con las que se realizarán las actividades de investigación, bioprospección y/o aprovechamiento comercial o industrial?',
		'numero_aprendices'     => 'número de aprendices beneficiados',
        'actividades_muestreo'  => 'actividad que pretende con la especie nativa',
        'objetivo_muestreo'     => 'finalidad de las actividades a realizar con la especie nativa',
		'municipios'            => 'nombre de los municipios beneficiados',
		'convocatoria_rol_sennova_id' => 'rol SENNOVA',
		'proyecto_presupuesto_id'     => 'rubro presupuestal',
		'descripcion_recursos_dinero' => 'descripción de la destinación del dinero aportado',
		'actividades_transferencia_conocimiento' => 'actividades de transferencia de conocimiento',
		'numero_roles'  		=> 'número de personas',
		'numero_meses' 			=> 'número de meses',
		'resultado_id' 			=> 'resultado',
		'producto_id'           => 'producto',
		'actividad_id'          => 'actividad',
		'carta_intencion'       => 'carta de intención',
		'fecha_inicio'          => 'fecha de inicio',
        'fecha_finalizacion'    => 'fecha de finalizacion',
		'numero_documento'      => 'número de documento',
		'numero_celular'      	=> 'número de celular',
		
		'grupo_investigacion_id' => 'grupo de investigación',
		'linea_investigacion_id' => 'línea de investigación',
		'min_fecha_inicio_proyectos' 		=> 'rango de fechas de ejecución de proyectos',
		'max_fecha_finalizacion_proyectos' 	=> 'rango de fechas de ejecución de proyectos',
		
        // Usuarios - Users
        'academic_centre_id'    => 'centro de formación',
        'email'                 => 'correo electrónico',
        'document_type'         => 'tipo de documento',
        'document_number'       => 'número de documento',
        'cellphone_number'      => 'número de celular',
        'participation_type'    => 'tipo de participación',
        'is_enabled'            => 'habilitado',
        'role_id'               => 'rol',

        // Centro de formación - Academic centre
        'regional_id'           => 'regional',
		'code'                  => 'código',
		'regional_director_id'  => 'Director(a) Regional',
		'deputy_director_id'  	=> 'Subdirector del Centro de Formación',

        // Programa de formación - Academic program
        'study_mode'            => 'modalidad',

        // Actividad - Activity
        'specific_objective_id'     => 'objetivo específico',
        'output_id'                 => 'productos',
        'project_sennova_budget_id' => 'rubros de proyecto',

        // Anexos - Annexes
        'linea_programatica_id'  => 'línea programática',

        // Rubro de convocatoria - Call budget
        'sennova_budget_id'     => 'rubro sennova',
        'call_id'               => 'convocatoria',

        // Convocatoria - Call
        'active'                => 'activo',
        'project_fecha_inicio'    => 'fecha límite de inicio de proyectos',
        'project_fecha_finalizacion'      => 'fecha límite de cierre de proyectos',

        // Rol SENNOVA de convocatoria - Call sennova role
        'salary'                => 'asingación mensual',
        'qty_months'            => 'cantidad de meses',
        'qty_roles'             => 'cantidad de roles',

        // Impacto - Impact
        'type'                  => 'tipo',

        // Disciplina de subárea de conocimiento - Knowledge subarea discipline
        'knowledge_subarea_id'  => 'subárea de conocimiento',

        // Subárea de conocimiento - Knowledge subarea
        'knowledge_area_id'     => 'área de conocimiento',

        // Estudio de mercado - Market research
        'first_price_quote'         => 'valor del soporte/cotización',
        'first_company_name'        => 'nombre de la compañía',
        'first_price_quote_file'    => 'archivo',
        'second_price_quote'        => 'valor del soporte/cotización',
        'second_company_name'       => 'nombre de la compañía',
        'second_price_quote_file'   => 'archivo',
        'third_price_quote'         => 'valor del soporte/cotización',
        'third_company_name'        => 'nombre de la compañía',
        'third_price_quote_file'    => 'archivo',

        // Subtipología Minciencias - MincienciasSubtypology
        'minciencias_typology_id'   => 'tipología Minciencias',

        // Producto - Output
        'project_result_id'         => 'resultado',
		'trl'                       => 'TRL',

        // Entidad aliada - Partner organization
		'partner_organization_type' => 'tipo de entidad aliada',
		'nit'                       => 'NIT',
		'legal_status'              => 'estado legal',
		'company_type'              => 'tipo de entidad',
		'agreement_description'     => 'descripción del convenio',
		'research_group'            => 'grupo de investigación',
		'gruplac_code'              => 'código GrupLac',
		'gruplac_link'              => 'enlace GrupLac',
		'knowledge_transfer_activities' => 'actividades de transferencia de conocimiento',
		'letter_of_intent'          => 'carta de intención',
		'intellectual_property'     => 'propiedad intelectual',
        'in_kind'                   => 'recursos en especie',
        'in_kind_description'       => 'descripción',
        'funds'                     => 'recursos',
        'funds_description'         => 'descripción',
        'activity_id'               => 'actividades',

        // Tema priorizado - Prioritized topic
        'productive_sector_id'      => 'sector productivo',
		'technical_committee_id'    => 'mesa técnica de servicios tecnológicos',

        // Línea programática - Programmatic line
		'project_category'          => 'categoría del proyecto',

        // Anexo de proyecto - Project annexe
        'annexe_id'                 => 'anexo',
		'file'                      => 'archivo',

        // Estudio de mercado - Market research
        'qty_items'                 => 'cantidad de ítems',
        'fact_sheet'                => 'ficha técnica',

        // Resultado - Project result
		'indicator'                 => 'indicador',
        'means_of_verification'     => 'medios de verificación',

        // Rubro de proyecto - Project sennova budget
        'call_budget_id'            => 'rubro de convocatoria',
		'justification'             => 'justificación',
        'value'                     => 'valor',

        // Rol de proyecto - Project sennova role
        'call_sennova_role_id'      => 'rol de convocatoria',

        // Tipo de proyecto - Project type
		'maximum_value'             => 'valor máximo',

        // Proyecto I+D+i - RDI
        'project_type_id'                           => 'tipo de proyecto',
        'linea_investigacion_id'                          => 'línea de investigación',
		'disciplina_subarea_conocimiento_id'           => 'disciplina de la subárea de conocimiento',
        'tematica_estrategica_id'                     => 'temáticas estratégicas SENA',
		'red_conocimiento_id'                      => 'red de conocimiento',
		'title'                                     => 'título',
		'abstract'                                  => 'resumen ',
		'antecedentes'                        => 'antecedentes del proyecto',
		'marco_conceptual'                      => 'marco conceptual',
		'metodologia'                       => 'metodología del proyecto',
		'propuesta_sostenibilidad'                   => 'propuesta de sostenibilidad',
		'objetivo_general'                         => 'objetivo general',
		'bibliografia'                              => 'bibliografía',
		
        
        'impacto_'                             => 'descripción del beneficio en los municipios',
        'impacto_centro_formacion'                           => 'impacto académico',
        'relacionado_plan_tecnologico'           => '¿El proyecto se alinea con el plan tecnológico desarrollado por el centro de formación?',
        'relacionado_agendas_competitividad'   => '¿El proyecto se alinea con las Agendas Departamentales de Competitividad e Innovación?',
        'relacionado_mesas_sectoriales'       => '¿El proyecto se alinea con las Mesas Sectoriales?',
        'relacionado_tecnoacademia'               => '¿El proyecto se formuló en conjunto con la tecnoacademia?',
        'sector_based_committee_id'                 => 'mesa sectorial',
        'technological_line_id'                     => 'línea tecnológica',
        'justificacion_industria_4'                  => 'industria 4.0',
		'justificacion_economia_naranja'              => 'economía naranja',
		'justificacion_politica_discapacidad'         => 'Política Institucional para Atención de las Personas con discapacidad',
		'planteamiento_problema'                         => 'planteamiento del problema',
		'justificacion_problema'                     => 'justificacion',

        // Grupo de investigación - Research group
		'acronym'               => 'acrónimo del grupo de investigación',
		'minciencias_code'      => 'código Minciencias',
		'minciencias_category'  => 'categoría Minciencias',

        // Línea de investigación - Research line
		'research_group_id' => 'grupo de investigación',

        // Análisis de riesgos - Risk analysis
		'level'                 => 'nivel',
		'impact'                => 'impacto',
		'probability'           => 'probabilidad',
		'effects'               => 'efectos',
		'mitigation_measures'   => 'medidas de mitigación',

        // Rubro SENNOVA - SENNOVA budget
        'first_budget_info_id'              => 'nombre de cuenta',
        'second_budget_info_id'             => 'concepto interno SENA',
        'third_budget_info_id'              => 'rubro',
        'budget_usage_id'                   => 'uso presupuestal',
        'requires_third_market_research'    => '¿Requiere de un tercer estudio de mercado?',
        'requires_market_research_batch'    => 'requiere múltiples estudios de mercados',
        'requires_market_research'          => 'requiere de estudio de mercado',
        'can_be_added'                      => 'puede sumar al total del proyecto',
        'message'                           => 'mensaje',
        'max_value'                         => 'valor máximo',
        'true'                              => 'si',

        // Rol SENNOVA - SENNOVA role
        'academic_degree'   => 'nivel educativo',

        // Objetivo específico - Specific objective
        'number'            => 'número',

        // Roles - Role
        'permission_id'     => 'permisos',

		'objectives_tree'   => 'árbol de objetivos',
        'problem_tree'      => 'árbol de problemas',
		'indirect_code'     => 'código indirecto',
		'value_chain'       => 'cadena de valor',
		'password'          => 'contraseña',
	],
];
