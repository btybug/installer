@extends('layouts.admin')
@section('content')

    <div class="row">
        <div class="col-lg-1"></div>
        <div class="col-lg-10">
            <h1>Avatar Composer</h1>
            <hr/>
            <h3>Commands:</h3>
            <div class="form-inline">
                <input type="text" id="path" style="width:300px;" class="form-control disabled"
                       placeholder="" value="{!! base_path('app/ExtraModules') !!}"/>
                <button id="install" onclick="call('install')" class="btn btn-success disabled">install</button>
                <button id="update" onclick="call('update')" class="btn btn-success disabled">update</button>
                <button id="update" onclick="call('dump-autoload')" class="btn btn-success disabled">dump-autoload
                </button>
            </div>
            <div class="form-inline">
                <br/><br/>
                <input type="text" id="package" style="width:300px;" class="form-control disabled"
                       placeholder="sahak.avatar/provaldation:dev-master" value=""/>
                <button id="install" onclick="call('require')" class="btn btn-success disabled">Require plugin</button>
            </div>
            <h3>Console Output:</h3>
            <pre id="output" class="well"></pre>
        </div>
        <div class="col-lg-1"></div>
    </div>
@stop
@section('CSS')
    <style>
        #output {
            width: 100%;
            height: 350px;
            overflow-y: scroll;
            background: black;
            color: darkturquoise;
            font-family: monospace;
        }
    </style>
@stop
@section('JS')
    <script type="text/javascript">
        $(document).ready(function () {
            check();
        });
        function url() {
            return '/admin/avatar/main';
        }
        function call(func) {
            $("#output").append("\nplease wait...\n");
            $("#output").append("\n===================================================================\n");
            $("#output").append("Executing Started");
            $("#output").append("\n===================================================================\n");
            $.post('/admin/avatar/main',
                {
                    "path": $("#path").val(),
                    "package":$("#package").val(),
                    "command": func,
                    "function": "command",
                    "_token": "<?php echo csrf_token()?>"

                },
                function (data) {
                    $("#output").append(data);
                    $("#output").append("\n===================================================================\n");
                    $("#output").append("Execution Ended");
                    $("#output").append("\n===================================================================\n");
                }
            );
        }
        function check() {
            $("#output").append('\nloading...\n');
            $.post(url(),
                {
                    "function": "getStatus",
                    "password": $("#password").val(),
                    "_token": "<?php echo csrf_token()?>"
                },
                function (data) {
                    if (data.composer_extracted) {
                        $("#output").html("Ready. All commands are available.\n");
                        $("button").removeClass('disabled');
                    }
                    else if (data.composer) {
                        $.post(url(),
                            {
                                "password": $("#password").val(),
                                "function": "extractComposer",
                                "_token": "<?php echo csrf_token()?>"
                            },
                            function (data) {
                                $("#output").append(data);
                                window.location.reload();
                            }, 'text');
                    }
                    else {
                        $("#output").html("Please wait till composer is being installed...\n");
                        $.post(url(),
                            {
                                "password": $("#password").val(),
                                "function": "downloadComposer",
                                "_token": "<?php echo csrf_token()?>"
                            },
                            function (data) {
                                $("#output").append(data);
//                                check();
                            }, 'text');
                    }
                });
        }
    </script>
@stop