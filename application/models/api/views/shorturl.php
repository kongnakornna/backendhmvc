<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>Short url by TruePlookpanya</title>        
        <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">        
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>        
        <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
        <script src="http://ajax.googleapis.com/ajax/libs/angularjs/1.3.14/angular.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/ng-clip/0.2.6/ng-clip.min.js"></script>
    </head>
    <body data-ng-app="app" data-ng-controller="HomeCtrl">                
        <div class="container">
            <div class="row">
                <h1 class="page-header">Short url <small>by trueplookpanya</small></h1>
                <div class="col-md-12">
                    <form method="post" data-ng-submit="Converter()">
                        <div class="col-md-6">
                            <input type="text" class="form-control" data-ng-model="data.longUrl" placeholder='Long url is here...'><br>
                            <button type="submit" class="btn btn-info">Convert</button>
                        </div>
                        <div class="col-md-6">
                            <input type="text" class="form-control" data-ng-model="data.shortUrl" placeholder="Short url is here..."><br>                            
                        </div>
                    </form>
                </div>
            </div>            
        </div>
    </body>
    <script>
        angular.module('app', ['Controller']);
        angular.module('Controller', [])
                .controller('HomeCtrl', function($scope, $http) {
                    var url = "https://www.googleapis.com/urlshortener/v1/url";
                    $scope.data = {};
                    $scope.Converter = function() {
                        var req = {
                            method: 'POST',
                            url: url,
                            header: {
                                'Content-Type': 'application/json'
                            },
                            data: {
                                longUrl: $scope.data.longUrl
                            }
                        };
                        $http(req).then(function(resp) {
                            console.log(resp.data);
                        });
                    };
                });
    </script>
    <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" async defer>
    </script>
</html>
