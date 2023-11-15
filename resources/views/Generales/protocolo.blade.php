@extends('layouts.app')

@section('content')
<style>
    .card {
        display: flex;
        align-items: center;
    }
    .card-body {
        max-width: 800px;
        background-color: white !important;
    }
    .titulo-card {
        border: solid 0.5px grey;
        border-radius: 10px;
        padding: 5px 10px 0;
        text-align: center;
        margin-bottom: 20px;
    }
</style>
<section class="section">
    <div class="section-header">
        <h3 class="page__heading">Protocolo para la Prevención, Atención y Seguimiento de Casos de Violencia</h3>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="titulo-card">
                            <h5>Protocolo para la Prevención, Atención y Seguimiento de Casos de Violencia<br>Resolución Reglamentaria N°…………	Registro IPRODHA</h5>
                        </div>
                        <p>En el IPRODHA se aprobó un Protocolo para la Prevención, Atención y Seguimiento de Casos de Violencia en el instituto, con vigencia a partir del 1º de octubre de 2023.</p>
                        <a href="{{ asset('storage/pdf/resolucion.pdf') }}" class="btn btn-primary" download>Ver Resolución Protocolo PDF</a>
                        <a href="{{ asset('storage/pdf/formulario_denuncia.pdf') }}" class="btn btn-primary" download>Bajar Formulario de Denuncia</a>
                        <br><br>
                        <p>Para tal fin, se ha creado una Comisión de Atención a la Violencia conformada por un equipo interdisciplinario que tiene como finalidad apoyar las acciones tendientes a brindar a todos los miembros de la institución, personal de servicios tercerizados del IPRODHA y público en gral., orientaciones y procedimientos de prevención, atención y seguimiento a situaciones de violencia y discriminación informadas, promoviendo así un ambiente libre de violencia y la aplicación de la legislación vigente especializada en la problemática.</p>
                        <p>Una de las funciones de esta Comisión será recepcionar las denuncias y/o informes de posibles casos de violencia para realizar la intervención correspondiente desde las competencias del Instituto.</p>
                        <h5>Integrantes de la Comisión:</h5>
                        - Nombre 1<br>
                        - Nombre 2<br>
                        - Nombre 3<br><br>
                        <p>Este Protocolo y la conformación de la Comisión se trata de una importante herramienta para que podamos entre todos prevenir y eliminar la violencia en el Iprodha.<br>Informáte sobre el Protocolo que entra en vigencia a partir del 01/10/2023 y ayudános a difundirlo.</p>

                        <h4>¿CÓMO REALIZAR LA DENUNCIA?</h4>
                        <ol>
                            <li>Ingresar al botón WEB <b>“No a la Violencia”</b></li>
                            <li>Descargar el <b>Formulario de denuncia</b></li>
                            <li>Completar los datos del <b>Formulario.</b></li>
                            <li>Entregarlo de manera presencial en la <b>Dirección de RR-HH – Comisión de Atención a la Violencia</b> los sgtes. días y horarios:
                                <br>Lunes 07. 00 a 13.00 hs
                                <br>Miércoles 07. 00 a 13.00 hs
                                <br>viernes 07. 00 a 13.00 hs</li>
                            <li>Enviarlo de manera virtual al correo electrónico de la CAV: <a href="mailto:prevenciondelaviolencia@iprodha.gov.ar">prevenciondelaviolencia@iprodha.gov.ar</a></li>
                        </ol>

                        <h4>EL PROTOCOLO SE GUÍA POR LOS PRINCIPIOS DE:</h4>
                        <ul>
                            <li>Atención personalizada.</li>
                            <li>Confidencialidad</li>
                            <li>Imparcialidad y legalidad</li>
                            <li>Derecho de defensa</li>
                            <li>Diligencia y celeridad</li>
                            <li>Amplitud probatoria</li>
                            <li>No revictimización</li>
                        </ul>

                        <h4>ASPECTOS A TENER EN CUENTA</h4>
                        <ul>
                            <li>Las denuncias deben realizarse dentro de los 6 (seis)meses de ocurrido el hecho. Luego será registrada a los fines de diagnóstico sin lugar al procedimiento sancionatorio.</li>
                            <li>Bajo ninguna circunstancia se dará curso a una denuncia anónima.</li>
                        </ul>

                        <h4>A QUIÉNES INVOLUCRA</h4>
                        <ul>
                            <li>Personal Instituto.</li>
                            <li>Autoridades Instituto</li>
                            <li>Personal de servicios tercerizados.</li>
                            <li>Público en general.</li>
                        </ul>
                        {{--
                        <p>{{$pathResolucion}}</p>
                        <p>{{$pathFormulario}}</p>--}}

                        {{--
                        <a href="{{ asset('pdf/pdf1.pdf') }}" class="btn btn-primary" download>Descargar PDF 1</a>
                        <a href="{{ asset('pdf/pdf2.pdf') }}" class="btn btn-primary" download>Descargar PDF 2</a>--}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
