<!DOCTYPE html>
<html lang="es">
   <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <meta name="csrf-token" content="{{ csrf_token() }}">
      <title>Resumen Proyecto {{(!empty($proyecto->idi))?'I+D+I':'TA'}} SGPS-8{{$proyecto->id}}-2021 - SGPS-SIPRO</title>
      <!-- Fonts -->
      <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&amp;display=swap">
      <!-- Styles -->
      <link rel="stylesheet" href="/css/app.css">
      <style>
            /** Define the margins of your page **/
            @page {
                margin: 230px 25px 60px 25px;
                font-family: 'Nunito', Roboto, Arial;
            }

            header {
                position: fixed;
                top: -210px;
                left: 0px;
                right: 0px;
                height: 150px;

                /** Extra personal styles **/
                text-align: center;
                line-height: 12px;
            }

            footer {
                position: fixed; 
                bottom: -40px; 
                left: 0px; 
                right: 0px;
                height: 50px; 

                /** Extra personal styles **/
                text-align: center;
                line-height: 35px;
            }
            body main{
               font-size: 12px;
            }
            .title{
               font-weight: bold;
            }
            .border{
               border: 1px solid black;
               padding: 3px;
            }
            .rotate90{
               transform: rotate(90deg);
               margin-top: 0.8in;
            }
            .page_break { page-break-before: always; }
        </style>
   </head>
   <body>
      <!-- Define header and footer blocks before your content -->
      <header>
         <table width="100%" border="1" cellspacing="0" cellpadding="10">
            <tr>
               <td rowspan="2"  valign="middle" align="center" width="20%">
                  <div>
                     <img src="{{asset('/images/Sena_Colombia_logo.png')}}" alt="Logo SENA">
                  </div>
               </td>
               <td valign="middle" align="center">
                  <p>Resumen Proyecto {{(!empty($proyecto->idi))?'I+D+I':'TA'}} - SGPS-SIPRO <br> <small>Código Proyecto: SGPS-8{{$proyecto->id}}-2021</small></p>
               </td>
            </tr>
            <tr>
               <td valign="middle" align="center">
                  <p><small>{{$datos->titulo}}</small></p>
               </td>
            </tr>
         </table>
      </header>

      <footer>
            <small>Copyright - SGPS-SIPRO &copy; <?php echo date("Y");?> </small>
      </footer>

      <!-- Wrap the content of your PDF inside a main tag -->
      <main> 
         <table width="100%" border="1" cellspacing="0" cellpadding="3">
            <tr>
               <td align="left">
                  <p class="title">Centro de formación</p>
               </td>
               <td align="left">
                  <p>{{$proyecto->centroFormacion->codigo}} - {{$proyecto->centroFormacion->nombre}}, Regional {{$proyecto->centroFormacion->regional->nombre}}</p>
               </td>
            </tr>
            @if($datos->lineaInvestigacion)
            <tr>
               <td align="left">
                  <p class="title">Línea de investigación</p>
               </td>
               <td align="left">
                  <p>{{$datos->lineaInvestigacion->nombre}}</p>
               </td>
            </tr>
            @endif
            <tr>
               <td align="left">
                  <p class="title">Código dependencia presupuestal (SIIF)</p>
               </td>
               <td align="left">
                  <p>Linea programatica: {{$proyecto->lineaProgramatica->codigo}} - {{$proyecto->lineaProgramatica->nombre}}</p>
               </td>
            </tr>
            @if($datos->redConocimiento)
            <tr>
               <td align="left">
                  <p class="title">Red de conocimiento sectorial</p>
               </td>
               <td align="left">
                  <p>{{$datos->redConocimiento->nombre}}</p>
               </td>
            </tr>
            @endif
            @if($datos->disciplinaSubareaConocimiento)
            <tr>
               <td align="left">
                  <p class="title">Disciplina de la subárea de conocimiento</p>
               </td>
               <td align="left">
                  <p>{{$datos->disciplinaSubareaConocimiento->nombre}}</p>
               </td>
            </tr>
            @endif
            @if($datos->disciplinaSubareaConocimiento)
            <tr>
               <td align="left" width="35%">
                  <p class="title">¿En cuál de estas actividades económicas se puede aplicar el proyecto de investigación?</p>
               </td>
               <td align="left">
                  <p>{{$datos->disciplinaSubareaConocimiento->nombre}}</p>
               </td>
            </tr>
            @endif
            @if($datos->tematicaEstrategica)
            <tr>
               <td align="left">
                  <p class="title">Temática estratégica SENA</p>
               </td>
               <td align="left">
                  <p>{{$datos->tematicaEstrategica->nombre}}</p>
               </td>
            </tr>
            @endif
            @if($datos->video)
            <tr>
               <td align="left">
                  <p class="title">¿El proyecto tiene video?</p>
               </td>
               <td align="left">
                  <p><a target="_blank" href="{{$datos->video}}">{{$datos->video}}</a></p>
               </td>
            </tr>
            @endif
         </table>
         @if($proyecto->idi)
         <table width="100%" border="1" cellspacing="0" cellpadding="3" style="border-top: none;">
            <tr>
               <td style="border-top: none;" width="35%" align="left">
                  <p class="title">¿El proyecto está relacionado con la industria 4.0?</p>
               </td>
               <td style="border-top: none;" width="5%" align="left">
                  <p>{{(!empty($datos->justificacion_industria_4))?'SI':'NO'}}</p>
               </td>
               <td style="border-top: none;" align="left">
                  <p>{{$datos->justificacion_industria_4}}</p>
               </td>
            </tr>
            <tr>
               <td width="35%" align="left">
                  <p class="title">¿El proyecto está relacionado con la economía naranja?</p>
               </td>
               <td width="5%" align="left">
                  <p>{{(!empty($datos->justificacion_economia_naranja))?'SI':'NO'}}</p>
               </td>
               <td align="left">
                  <p>{{$datos->justificacion_economia_naranja}}</p>
               </td>
            </tr>
            <tr>
               <td width="35%" align="left">
                  <p class="title">¿El proyecto aporta a la Política Institucional para Atención de las Personas con discapacidad?</p>
               </td>
               <td width="5%" align="left">
                  <p>{{(!empty($datos->justificacion_politica_discapacidad))?'SI':'NO'}}</p>
               </td>
               <td align="left">
                  <p>{{$datos->justificacion_politica_discapacidad}}</p>
               </td>
            </tr>
         </table>
         <table width="100%" border="1" cellspacing="0" cellpadding="3" style="border-top: none;">
            <tr>
               <td colspan="2"><p class="title">¿Cuál es el origen de las muestras con las que se realizarán las actividades de investigación, bioprospección y/o aprovechamiento comercial o industrial?</p></td>
            </tr>
            @if($datos->muestreo==1)
            <tr>
               <td colspan="2" align="left">
                  <p>Especies Nativas. (es la especie o subespecie taxonómica o variedad de animales cuya área de disposición geográfica se extiende al territorio nacional o a aguas jurisdiccionales colombianas o forma parte de los mismos comprendidas las especies o subespecies que migran temporalmente a ellos, siempre y cuando no se encuentren en el país o migren a él como resultado voluntario o involuntario de la actividad humana. Pueden ser silvestre, domesticada o escapada de domesticación incluyendo virus, viroides y similares)</p>
               </td>
            </tr>
            <tr>
               <td align="left">
                  <p class="title">¿Qué actividad pretende realizar con la especie nativa?</p>
               </td>
               <td align="left">
                  <p class="title">¿Cuál es la finalidad de las actividades a realizar con la especie nativa/endémica?</p>
               </td>
            </tr>
            <tr>
               @if($datos->actividades_muestreo=="1.1.1")
               <td align="left">
                  <p>Separación de las unidades funcionales y no funcionales del ADN y el ARN, en todas las formas que se encuentran en la naturaleza.</p>
               </td>
               @elseif($datos->actividades_muestreo=="1.1.2")
               <td align="left">
                  <p>Aislamiento de una o varias moléculas, entendidas estas como micro y macromoléculas, producidas por el metabolismo de un organismo.</p>
               </td>
               @elseif($datos->actividades_muestreo=="1.1.3")
               <td align="left">
                  <p>Solicitar patente sobre una función o propiedad identificada de una molécula, que se ha aislado y purificado.</p>
               </td>
               @elseif($datos->actividades_muestreo=="1.1.4")
               <td align="left">
                  <p>No logro identificar la actividad a desarrollar con la especie nativa</p>
               </td>
               @endif
               @if($datos->objetivo_muestreo=="1.2.1")
               <td align="left">
                  <p>Investigación básica sin fines comerciales.</p>
               </td>
               @elseif($datos->objetivo_muestreo=="1.2.2")
               <td align="left">
                  <p>Bioprospección en cualquiera de sus fases.</p>
               </td>
               @elseif($datos->objetivo_muestreo=="1.2.3")
               <td align="left">
                  <p>Comercial o Industrial.</p>
               </td>
               @endif
            </tr>
            @elseif($datos->muestreo==2)
            <tr>
               <td colspan="2">
                  <p>Especies Introducidas. (son aquellas que no son nativas de Colombia y que ingresaron al país por intervención humana)</p>
               </td>
            </tr> 
            @elseif($datos->muestreo==3)
            <tr>
               <td colspan="2">
                  <p>Recursos genéticos humanos y sus productos derivados</p>
               </td>
            </tr>
            @elseif($datos->muestreo==4)
            <tr>
               <td colspan="2">
                  <p>Intercambio de recursos genéticos y sus productos derivados, recursos biológicos que los contienen o los componentes asociados a estos. (son aquellas que realizan las comunidades indígenas, afroamericanas y locales de los Países Miembros de la Comunidad Andina entre sí y para su propio consumo, basadas en sus prácticas consuetudinarias)</p>
               </td>
            </tr>
            @elseif($datos->muestreo==5)
            <tr>
               <td colspan="2">
                  <p>Recurso biológico que involucren actividades de sistemática molecular, ecología molecular, evolución y biogeografía molecular (siempre que el recurso biológico se haya colectado en el marco de un permiso de recolección de especímenes de especies silvestres de la diversidad biológica con fines de investigación científica no comercial o provenga de una colección registrada ante el Instituto Alexander van Humboldt)</p>
               </td>
            </tr>
            @elseif($datos->muestreo==6)
            <tr>
               <td colspan="2">
                  <p>No aplica</p>
               </td>
            </tr>
            @endif
         </table>
         <table width="100%" border="1" cellspacing="0" cellpadding="3" style="border-top: none;">
            <tr>
               <td>
                  <p class="title">¿El proyecto se alinea con el plan tecnológico desarrollado por el centro de formación?</p>
               </td>
               <td width="5%">
                  <p class="title">{{$datos->relacionado_plan_tecnologico['label']}}</p>
               </td>
            </tr>
            <tr>
               <td>
                  <p class="title">¿El proyecto se alinea con las Agendas Departamentales de Competitividad e Innovación?</p>
               </td>
               <td  width="5%">
                  <p class="title">{{$datos->relacionado_plan_tecnologico['label']}}</p>
               </td>
            </tr>
         </table>
         <table width="100%" border="1" cellspacing="0" cellpadding="3" style="border-top: none;">
            @if($datos->relacionado_mesas_sectoriales==1)
            <tr>
               <td width="35%">
                  <p class="title">Alineación con las Mesas Sectoriales</p>
               </td>
               <td>
                  <ol>
                  @foreach($datos->mesasSectoriales()->orderBy('nombre','asc')->cursor() as $mesa)
                     <li>{{$mesa->nombre}}</li>
                  @endforeach
                  </ol>
               </td>
            </tr>
            @endif
         </table>
         @endif
         <table width="100%" border="1" cellspacing="0" cellpadding="3" style="border-top: none;">
            @if($proyecto->tecnoacademiaLineasTecnoacademia()->count()>0)
            <tr>
               <td width="35%">
                  <p class="title">Tecnoacademia con la cual articuló el proyecto</p>
               </td>
               <td>
                  <p>{{$proyecto->tecnoacademiaLineasTecnoacademia()->first()->tecnoacademia->nombre}}
                  <br>Lineas Tecnologicas:</p>
                  <ol>
                  @foreach($proyecto->tecnoacademiaLineasTecnoacademia as $linea)
                     <li>{{$linea->lineaTecnologica->nombre}}</li>
                  @endforeach
                  </ol>
               </td>
            </tr>
            @endif
         </table>
         <div class="border">
            <p class="title">Resumen del proyecto</p>
            <p>{{$datos->resumen}}</p>
         </div>
         <div class="border">
            <p class="title">Antecedentes</p>
            <p>{{$datos->antecedentes}}</p>
         </div>
         <div class="border">
            <p class="title">Marco conceptual</p>
            <p>{{$datos->marco_conceptual}}</p>
         </div>
         <div class="border">
            <p class="title">Metodología</p>
            <p>{{$datos->metodologia}}</p>
         </div>
         <div class="border">
            <p class="title">Propuesta de sostenibilidad</p>
            <p>{{$datos->propuesta_sostenibilidad}}</p>
         </div>
         <div class="border">
            <p class="title">Bibliografía</p>
            <p>{{$datos->bibliografia}}</p>
         </div>
         @if($proyecto->idi)
         <table width="100%" border="1" cellspacing="0" cellpadding="3" style="border-top: none;">
            <tr>
               <td class="title">Número de los aprendices que se beneficiarán en la ejecución del proyecto</td>
               <td>{{$datos->numero_aprendices}}</td>
            </tr>
         </table>
         @endif
         <table class="page_break" width="100%" border="1" cellspacing="0" cellpadding="3" style="border-top: none;">
            <tr>
               <td width="35%" class="title">Nombre de los municipios beneficiados</td>
               <td>
                  <ol>
                     @foreach($proyecto->municipios as $mun)
                     <li>{{$mun->nombre}}</li>
                     @endforeach
                  </ol>
               </td>
            </tr>
            <tr>
               <td width="35%" class="title">Descripción del beneficio en los municipios</td>
               <td>
                  <p>{{$datos->impacto_municipios}}</p>
               </td>
            </tr>
            <tr>
               <td width="35%" class="title">Impacto en el centro de formación</td>
               <td>
                  <p>{{$datos->impacto_centro_formacion}}</p>
               </td>
            </tr>
         </table>
         <div class="rotate90 page_break">
            <img class="" src="data:image/png;base64,{{$base64Arbolproblemas}}" alt="Árbol de problemas" width="100%">
         </div>
         <div class="rotate90">
            <img class="" src="data:image/png;base64,{{$base64Arbolobjetivos}}" alt="Árbol de objetivos" width="100%">
         </div>
         
         <div class="border page_break">
            <p class="title">Planteamiento del problema</p>
            <p>{{$datos->planteamiento_problema}}</p>
         </div>
         <div class="border">
            <p class="title">Justificación</p>
            <p>{{$datos->justificacion_problema}}</p>
         </div>
         <div class="border">
            <p class="title">Objetivo general</p>
            <p>{{$datos->objetivo_general}}</p>
         </div>
         
         <table class=" page_break" width="100%" border="1" cellspacing="0" cellpadding="3" style="border-top: none;">
            <tr>
               <td class="title" colspan="2">Efectos Directos</td>
            </tr>
         </table>
         @foreach($proyecto->efectosDirectos as $efeDir)
         <table width="100%" border="1" cellspacing="0" cellpadding="3" style="border-top: none;">
            <tr>
               <td class="title" width="15%">EFE-{{$efeDir->id}}</td>
               <td colspan="{{($efeDir->efectosindirectos()->count()>=2)?$efeDir->efectosindirectos()->count()-1:''}}">{{$efeDir->descripcion}}</td>
            </tr>
               <tr>
                  @foreach($efeDir->efectosindirectos as $efeind)
                  <td width="{{100/$efeDir->efectosindirectos()->count()}}%" valign="top">
                     <span class="title">Efecto indirecto EFE-{{$efeDir->id}}-IND-{{$efeind->id}}:</span>
                     <br>{{$efeind->descripcion}}
                  </td>
                  @endforeach
               </tr>
         </table>
         @endforeach
         
         <table class=" page_break" width="100%" border="1" cellspacing="0" cellpadding="3" style="border-top: none;">
            <tr>
               <td class="title" colspan="2">Causas Directas</td>
            </tr>
         </table>
         @foreach($proyecto->causasDirectas as $cauDir)
         <table width="100%" border="1" cellspacing="0" cellpadding="3" style="border-top: none;">
            <tr>
               <td class="title" width="15%">CAU-{{$cauDir->id}}</td>
               <td colspan="{{($cauDir->causasindirectas()->count()>=2)?$cauDir->causasindirectas()->count()-1:''}}">{{$cauDir->descripcion}}</td>
            </tr>
               <tr>
                  @foreach($cauDir->causasindirectas as $cauind)
                  <td width="{{100/$cauDir->causasindirectas()->count()}}%" valign="top">
                     <span class="title">Efecto indirecto CAU-{{$cauDir->id}}-IND-{{$cauind->id}}:</span>
                     <br>{{$cauind->descripcion}}
                  </td>
                  @endforeach
               </tr>
         </table>
         @endforeach
         
         <table class=" page_break" width="100%" border="1" cellspacing="0" cellpadding="3" style="border-top: none;">
            <tr>
               <td class="title" colspan="2">Objetivos Específicos</td>
            </tr>
         </table>
         @foreach($proyecto->causasDirectas as $cauDir)
         <table width="100%" border="1" cellspacing="0" cellpadding="3" style="border-top: none;">
            <tr>
               <td width="15%">
                  <span class="title">OBJ-ESP-{{$cauDir->objetivoEspecifico->id}}</span><br>
                  <small>Causa Directa: CAU-{{$cauDir->id}}</small>
               </td>
               <td colspan="{{($cauDir->causasindirectas()->count()>=2)?$cauDir->causasindirectas()->count()-1:''}}">{{$cauDir->objetivoEspecifico->descripcion}}</td>
            </tr>
               <tr>
                  @foreach($cauDir->causasindirectas as $cauind)
                  <td width="{{100/$cauDir->causasindirectas()->count()}}%" valign="top">
                     <span class="mb-3">
                        <span class="title">Actividad: OBJ-ESP-{{$cauDir->objetivoEspecifico->id}}-ACT-{{$cauind->actividad->id}}</span>
                        <small>Efecto indirecto CAU-{{$cauDir->id}}-IND-{{$cauind->id}}:</small><br>
                        <span class="title">Fecha de ejecución</span><br>
                        Del: {{$cauind->actividad->year_inicio}}-{{$cauind->actividad->mes_inicio}}-{{$cauind->actividad->dia_inicio}} hasta {{$cauind->actividad->year_finalizacion}}-{{$cauind->actividad->mes_finalizacion}}-{{$cauind->actividad->dia_finalizacion}}
                     </span>
                     <br>{{$cauind->actividad->descripcion}}
                  </td>
                  @endforeach
               </tr>
         </table>
         @endforeach
         
         <table class=" page_break" width="100%" border="1" cellspacing="0" cellpadding="3" style="border-top: none;">
            <tr>
               <td class="title" colspan="2">Resultados</td>
            </tr>
         </table>
         @foreach($proyecto->efectosDirectos as $efeDir)
            @foreach($efeDir->resultados as $resultado)
            <table width="100%" border="1" cellspacing="0" cellpadding="3" style="border-top: none;">
               <tr>
                  <td width="15%">
                  <span class="title">RES-{{$resultado->id}}</span><br>
                  <small>Efecto directo: EFE-{{$efeDir->id}}</small>
                  </td>
                  <td colspan="{{($efeDir->efectosindirectos()->count()>=2)?$efeDir->efectosindirectos()->count()-1:''}}">
                  {{$resultado->descripcion}}
                  </td>
               </tr>
                  <tr>
                     @foreach($efeDir->efectosindirectos as $efeind)
                     <td width="{{100/$efeDir->efectosindirectos()->count()}}%" valign="top">
                        <span class="mb-3">
                           <span class="title">RES-{{$resultado->id}}-IMP-{{$efeind->impacto->id}}</span><br>
                           <small>Efecto indirecto: EFE-{{$efeDir->id}}-IND-{{$efeind->id}}:</small>
                        </span>
                        <br>
                        <span class="title">Tipo:</span> {{($tiposImpacto->where('value',$efeind->impacto->tipo)->first())?$tiposImpacto->where('value',$efeind->impacto->tipo)->first()['label']:''}}.<br>
                        {{$efeind->impacto->descripcion}}
                     </td>
                     @endforeach
                  </tr>
            </table>
            @endforeach
         @endforeach
         
         <div class="page_break" style="text-align:center">
            <p class="title">Gant Actividades</p>
            <center>
               <img style="margin:auto; max-height:90%; max-width:100%;" src="data:image/png;base64,{{$base64GantActividades}}" alt="Gant Actividades">
            </center>
         </div>
         
         <div class="border page_break">
            <p class="title" style="text-align:center">Participantes</p>
            <table width="100%" border="1" cellspacing="0" cellpadding="3" style="border-top: none;">
               <thead slot="thead">
                  <tr>
                        <th>Nombre</th>
                        <th>Correo electrónico</th>
                        <th>Centro de formación</th>
                        <th>Regional</th>
                        <th>Rol SENNOVA</th>
                        <th>Meses</th>
                        <th>Horas</th>
                  </tr>
               </thead>
               <tbody slot="tbody">
               @foreach($proyecto->participantes as $part)
                  <tr>
                        <td>
                           <p>{{$part->nombre_usuario}}</p>
                        </td>
                        <td>
                           <p>{{$part->email}}</p>
                        </td>
                        <td>
                           <p>{{$part->centroFormacion->nombre}}</p>
                        </td>
                        <td>
                           <p>{{$part->centroFormacion->regional->nombre}}</p>
                        </td>
                        <td>
                           <p>{{($part->pivot->rol_id)?\App\Models\Role::find($part->pivot->rol_id)->nombre:'Sin información registrada'}}</p>
                        </td>
                        <td>
                           <p>{{$part->pivot->cantidad_meses}}</p>
                        </td>
                        <td>
                           <p>{{$part->pivot->cantidad_horas}}</p>
                        </td>
                  </tr>
               @endforeach
               </tbody>
            </table>
         </div>
         
         <div class="border">
            <p class="title" style="text-align:center">Semilleros de investigación</p>
            <table width="100%" border="1" cellspacing="0" cellpadding="3" style="border-top: none;">
               <thead slot="thead">
                  <tr>
                        <th>Nombre</th>
                        <th>Línea de investigación</th>
                        <th>Grupo de investigación</th>
                  </tr>
               </thead>
               <tbody slot="tbody">
               @foreach($proyecto->semillerosInvestigacion as $sem)
                  <tr>
                        <td>
                           <p>{{$sem->nombre}}</p>
                        </td>
                        <td>
                           <p>{{$sem->lineaInvestigacion->nombre}}</p>
                        </td>
                        <td>
                           <p>{{$sem->lineaInvestigacion->grupoInvestigacion->nombre}}</p>
                        </td>
                  </tr>
               @endforeach
               </tbody>
            </table>
         </div>
         
         <div class="border page_break">
            <p class="title" style="text-align:center">Productos</p>
            <img class="" src="data:image/png;base64,{{$base64GantProductos}}" alt="Gant Productos" width="100%">
            
            @foreach($proyecto->efectosDirectos as $efeDir)
               @foreach($efeDir->resultados as $resultado)
                  @foreach($resultado->productos as $prod)
                  <table width="100%" border="1" cellspacing="0" cellpadding="3" style="border-top: none; margin-top:20px;">
                     <tbody slot="thead">
                        <tr>
                              <th align="left" width="15%">Nombre</th>
                              <td colspan="3">{{$prod->nombre}}</td>
                        </tr>
                        <tr>
                              <th align="left" width="15%">Fecha de ejecución</th>
                              <td>Inicio: {{$prod->fecha_inicio}} - Fin: {{$prod->fecha_finalizacion}}</td>
                              <th align="left" width="15%">Código Resultado</th>
                              <td>RES-{{$prod->resultado_id}}</td>
                        </tr>
                        <tr>
                              <th align="left" width="15%">Indicador</th>
                              <td colspan="3">{{$prod->indicador}}</td>
                        </tr>
                        @if($prod->productoIdi)
                        <tr>
                              <th align="left" width="15%">Subtipologia Minciencias</th>
                              <td colspan="3">{{$prod->productoIdi->subtipologiaMinciencias->nombre}}</td>
                        </tr>
                        @elseif($prod->productoTaTp)
                        <tr>
                              <th align="left" width="15%">Valor proyectado</th>
                              <td colspan="3">{{$prod->productoTaTp->valor_proyectado}}</td>
                        </tr>
                        @endif
                     </tbody>
                  </table>
                  @endforeach
               @endforeach
            @endforeach
         </div>
         
         <div class="border page_break">
            <p class="title" style="text-align:center">Análisis de riesgos</p>
            
            @foreach($proyecto->analisisRiesgos as $riesgo)
               <table width="100%" border="1" cellspacing="0" cellpadding="3" style="border-top: none; margin-top:20px;">
                  <tbody slot="thead">
                     <tr>
                           <th align="left" width="15%">Nivel de riesgo</th>
                           <td>{{$riesgo->nivel}}</td>
                           <th align="left" width="15%">Tipo de riesgo</th>
                           <td>{{$riesgo->tipo}}</td>
                     </tr>
                     <tr>
                           <th align="left" width="15%">Descripción</th>
                           <td colspan="3">{{$riesgo->descripcion}}</td>
                     </tr>
                     <tr>
                           <th align="left" width="15%">Probabilidad</th>
                           <td>{{$riesgo->probabilidad}}</td>
                           <th align="left" width="15%">Impactos</th>
                           <td>{{$riesgo->impacto}}</td>
                     </tr>
                     <tr>
                           <th align="left" width="15%">Efectos</th>
                           <td colspan="3">{{$riesgo->efectos}}</td>
                     </tr>
                     <tr>
                           <th align="left" width="15%">Medidas de mitigación</th>
                           <td colspan="3">{{$riesgo->medidas_mitigacion}}</td>
                     </tr>
                  </tbody>
               </table>
            @endforeach
         </div>
         
         <div class="border">
            <p class="title" style="text-align:center">Anexos</p>
               <table width="100%" border="1" cellspacing="0" cellpadding="3" style="border-top: none; margin-top:20px;">
                  <tbody slot="thead">
                     @foreach($proyectoAnexo as $anexo)
                     <tr>
                        <th align="left" width="30%">{{$anexo->nombre}}</th>
                        <td>
                        {{(empty($anexo->archivo)?'N/A':'')}}
                        @if(!empty($anexo->archivo))
                        <a href="{{route('convocatorias.proyectos.proyecto-anexos.download', ['proyecto' => $proyecto->id, 'convocatoria' => $convocatoria->id, 'proyecto_anexo'=>$anexo])}}" target="blank" download>
                           <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                           {{route('convocatorias.proyectos.proyecto-anexos.download', ['proyecto' => $proyecto->id, 'convocatoria' => $convocatoria->id, 'proyecto_anexo'=>$anexo])}}
                        </a>
                        @endif
                        </td>
                     </tr>
                      @endforeach
                  </tbody>
               </table>
         </div>
         
         <div class="rotate90 page_break" style="margin-top: 2in !important">
            <p class="title" style="text-align:center">Cadena de valor</p>
            <img src="data:image/png;base64,{{$base64CadenaValor}}" alt="Cadena de valor" width="100%">
         </div>
      </main>
   </body>
</html>