<!DOCTYPE html>
<html>
    <head>
        <title>Trueplookpanya Hash</title>        
        <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">        
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>        
        <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
        <script src="http://crypto-js.googlecode.com/svn/tags/3.1.2/build/rollups/sha1.js"></script>
        <script src="http://ajax.googleapis.com/ajax/libs/angularjs/1.3.14/angular.min.js"></script>
        <style>
            .btn-toolbar .btn {
                margin-bottom: 5px; 
            }
        </style>
    </head>
    <body data-ng-app="app" data-ng-controller="hashCtrl">
        <div class="row">
            <div class="container">
                <h1 class="page-header">Trueplookpanya HASH <small>v1.0</small></h1>
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-6">
                            <form class="form-horizontal">
                                <div class="form-group">
                                    <label class="control-label col-md-2">Collections:</label>
                                    <div class="col-md-10">
                                        <input type="button" class="btn btn-info btn-toolbar" value="MD5" data-ng-click="data.func = 1" data-ng-class="{'active':(data.func == 1?true:false), 'btn-danger':(data.func == 1?true:false)}"> 
                                        <input type="button" class="btn btn-info btn-toolbar" value="SHA1" data-ng-click="data.func = 2" data-ng-class="{'active':(data.func == 2?true:false), 'btn-danger':(data.func == 2?true:false)}"> 
                                        <input type="button" class="btn btn-info btn-toolbar" value="SHA256" data-ng-click="data.func = 3" data-ng-class="{'active':(data.func == 3?true:false), 'btn-danger':(data.func == 3?true:false)}"> 
                                        <input type="button" class="btn btn-info btn-toolbar" value="SHA512" data-ng-click="data.func = 4" data-ng-class="{'active':(data.func == 4?true:false), 'btn-danger':(data.func == 4?true:false)}"> 
                                        <input type="button" class="btn btn-info btn-toolbar" value="SHA3" data-ng-click="data.func = 5" data-ng-class="{'active':(data.func == 5?true:false), 'btn-danger':(data.func == 5?true:false)}"> 
                                        <input type="button" class="btn btn-info btn-toolbar" value="RIPEMD-160" data-ng-click="data.func = 6" data-ng-class="{'active':(data.func == 6?true:false), 'btn-danger':(data.func == 6?true:false)}"> 
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-2">TEXT:</label>
                                    <div class="col-md-10">
                                        <textarea class="form-control" data-ng-model="data.txt" style="height: 250px;">
										
                                        </textarea>
                                    </div>                                        
                                </div> 
                                <div class="row">
                                    <div class="col-md-offset-2 col-md-10">
                                        <input type="button" class="btn btn-success btn-block" value="process" data-ng-click="processer()">
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-6" style="border-left: 2px solid #333">
                            <textarea data-ng-model="data.displayer" class="form-control" style="height: 348px">
                                
                            </textarea>                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12 text-center">
            <!--BEGIN WEB STAT Truehit CODE-->
            <!--BEGIN WEB STAT CODE-->
            <script type="text/javascript"> __th_page = "truelittlemonk<?echo isset($pagename)?'_'.$pagename:'';?>";</script>
            <script type="text/javascript" src="http://hits.truehits.in.th/data/t0029829.js"></script>
            <noscript> 
            <a target="_blank" href="http://truehits.net/stat.php?id=t0029829"><img src="http://hits.truehits.in.th/noscript.php?id=t0029829" alt="Thailand Web Stat" border="0" width="14" height="17" /></a> 
            <a target="_blank" href="http://truehits.net/">Truehits.net</a> 
            </noscript>
            <!-- END WEBSTAT CODE -->
            <script type="text/javascript" language="javascript1.1" src="http://tracker.stats.in.th/tracker.php?uid=34348"></script>
            <noscript>
            <a target="_blank" href="http://www.stats.in.th/">www.Stats.in.th</a>
            </noscript>
            <script type="text/javascript">
                                                                                                                    var _gaq = _gaq || [];
                                                                                                                    _gaq.push(['_setAccount', 'UA-24025527-1']);
                                                                                                                    _gaq.push(['_trackPageview']);
                                                                                                                    (function() {
                                                                                                                        var ga = document.createElement('script');
                                                                                                                        ga.type = 'text/javascript';
                                                                                                                        ga.async = true;
                                                                                                                        ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
                                                                                                                        var s = document.getElementsByTagName('script')[0];
                                                                                                                        s.parentNode.insertBefore(ga, s);
                                                                                                                    })();
            </script>
            <!-- END WEBSTAT Truehit CODE -->
        </div>
        <script src="http://crypto-js.googlecode.com/svn/tags/3.1.2/build/rollups/md5.js"></script>
        <script src="http://crypto-js.googlecode.com/svn/tags/3.1.2/build/rollups/sha1.js"></script>
        <script src="http://crypto-js.googlecode.com/svn/tags/3.1.2/build/rollups/sha256.js"></script>
        <script src="http://crypto-js.googlecode.com/svn/tags/3.1.2/build/rollups/sha512.js"></script>
        <script src="http://crypto-js.googlecode.com/svn/tags/3.1.2/build/rollups/sha3.js"></script>
        <script src="http://crypto-js.googlecode.com/svn/tags/3.1.2/build/rollups/ripemd160.js"></script>

        <script>
                                                                                                                            var app = angular.module("app", ["appCtrl"]);
                                                                                                                            app.run(function() {
                                                                                                                                console.log("Trueplookpanya hash"), console.log("engine start"), console.log("ready to use...")
                                                                                                                            }), app.directive("ngEnter", function() {
                                                                                                                                return function(a, t, e) {
                                                                                                                                    t.bind("keydown keypress", function(t) {
                                                                                                                                        13 === t.which && (a.$apply(function() {
                                                                                                                                            a.$eval(e.ngEnter)
                                                                                                                                        }), t.preventDefault())
                                                                                                                                    })
                                                                                                                                }
                                                                                                                            });
                                                                                                                            var ctrl = angular.module("appCtrl", []);
                                                                                                                            ctrl.controller("hashCtrl", ["$scope", function(a) {
                                                                                                                                    a.data = {txt: "", func: 1, displayer: ""}, a.processer = function() {
                                                                                                                                        switch (a.data.func) {
                                                                                                                                            case 1:
                                                                                                                                                a.data.displayer = CryptoJS.MD5(a.data.txt);
                                                                                                                                                break;
                                                                                                                                            case 2:
                                                                                                                                                a.data.displayer = CryptoJS.SHA1(a.data.txt);
                                                                                                                                                break;
                                                                                                                                            case 3:
                                                                                                                                                a.data.displayer = CryptoJS.SHA256(a.data.txt);
                                                                                                                                                break;
                                                                                                                                            case 4:
                                                                                                                                                a.data.displayer = CryptoJS.SHA512(a.data.txt);
                                                                                                                                                break;
                                                                                                                                            case 5:
                                                                                                                                                a.data.displayer = CryptoJS.SHA3(a.data.txt);
                                                                                                                                                break;
                                                                                                                                            case 6:
                                                                                                                                                a.data.displayer = CryptoJS.RIPEMD160(a.data.txt)
                                                                                                                                            }
                                                                                                                                    }
                                                                                                                                }]);
        </script>
    </body>

</html>