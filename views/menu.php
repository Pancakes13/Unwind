<?php
require ("../header.php");
include '../sidebar.php';
?>
 <!-- Content Wrapper. Contains page content -->
 <div class="content-wrapper">
  <section class="content" ng-app="unwindApp">
    <div ng-cloak ng-controller="floorController" data-ng-init="init()">
        <div layout="row" class="rightFloat">
            <md-button class="md-raised" id="createRoomButton" data-target="#insertMenu" data-toggle="modal">Add New Menu</md-button>
        </div>

        <div id="insertMenu" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <form ng-submit="createMenu()">
                    <div class="modal-content">
                        <div class="modal-header" id="createHeader">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h2>Create Menu</h2>
                        </div>
                        <div class="modal-body">
                            <input class="form-control" placeholder="Menu Name" ng-model="menuName" required>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success" onclick="$('#insertMenu').modal('hide');">Create Menu <span class="fa fa-edit"></span></button>
                            <button type="button" class="btn btn-danger" onclick="$('#insertMenu').modal('hide');">Close <span class="fa fa-close"></span></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
      <md-content>
        
        <md-tabs md-dynamic-height md-border-bottom>
          <md-tab ng-repeat="x in menuList track by $index" label="{{x.Name}}">
            <md-content>
                
                <div>
                    <md-button class="md-raised" style="color:white; background-color:green" data-target="#insertFood" data-toggle="modal">Add Food to Menu</md-button>
                </div>
                <div>
                    <md-button class="md-raised md-warn" data-target="#removeFood" data-toggle="modal">Remove Food from Menu</md-button>
                </div>

                <div id="insertFood" class="modal fade" role="dialog">
                    <div class="modal-dialog">
                        <form ng-submit="insertFood()">
                            <div class="modal-content">
                                <div class="modal-header" style="background-color:#003300; color:white;">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h2>Create Food</h2>
                                </div>
                                <div class="modal-body">
                                    <input ng-model="menuId" required>
                                    <input class="form-control" placeholder="Menu Name" ng-model="foodName" required>
                                    <textarea class="form-control" placeholder="Description" ng-model="foodDesc" required></textarea>
                                    <input class="form-control" placeholder="Price" ng-model="foodPrice" type="number" required>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-success" onclick="$('#insertFood').modal('hide');">Create Food <span class="fa fa-edit"></span></button>
                                    <button type="button" class="btn btn-danger" onclick="$('#insertFood').modal('hide');">Close <span class="fa fa-close"></span></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div id="removeFood" class="modal fade" role="dialog">
                    <div class="modal-dialog">
                        <form ng-submit="removeFood()">
                            <div class="modal-content">
                                <div class="modal-header" style="background-color:#003300; color:white;">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h2>Create Food</h2>
                                </div>
                                <div class="modal-body">
                                    
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-success" onclick="$('#removeFood').modal('hide');">Create Food <span class="fa fa-edit"></span></button>
                                    <button type="button" class="btn btn-danger" onclick="$('#removeFood').modal('hide');">Close <span class="fa fa-close"></span></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <md-list flex ng-repeat = "food in foodSet.food track by $index">
                    <md-list-item class="md-3-line rrList" ng-click="null">
                            <div>
                                <img src="{{food.Picture}}" class="logoPic">
                            </div>
                            <div>
                                <div>{{food.Name}}</div>
                                <div>{{food.Description}}</div>
                                <div>{{food.Price}} Php</div>
                            </div>  
                    </md-list-item>
                <md-list>            
            </md-content>
          </md-tab>
        </md-tabs>
      </md-content>
    </div>  
  </section>
</div>

<?php
include '../footer.php';
include '../control_sidebar.php';
?>
<!-- End of div wrapper-->
</div>
<!-- End of body-->
</body>

<script>
var active = angular.element( document.querySelector( '#foodTab' ) );
active.addClass('active');
var active2 = angular.element( document.querySelector( '#menuTab' ) );
active2.addClass('active');  

var app = angular.module('unwindApp', ['ngMaterial']);
app.controller('floorController', function($scope, $http, $mdDialog) {
    $scope.init = function () {
        $scope.foodSet = {food: []};
        $scope.food = [];

        $http.get("../queries/get/getMenu.php").then(function (response){
            $scope.menuList = response.data.records;
            for($x=0; $x<$scope.menuList.length; $x++){
                $http.get("../queries/get/getFoodFromMenu.php?menuId="+$scope.menuList[$x].MenuId).then(function (response){
                    $scope.foodPerMenu = response.data.records;
                    if($scope.foodPerMenu!=""){
                        $scope.foodSet.food = $scope.foodPerMenu;
                    }
                });
            }
        });
    };

    $scope.createMenu = function() {
        $http.post('../queries/insert/insertMenu.php', {
            'menuName': $scope.menuName
        }).then(function(data, status){
            $scope.init();
        })
    };

    $scope.insertFood = function() {
        $http.post('../queries/insert/insertFood.php', {
            'foodName': $scope.foodName,
            'foodDesc': $scope.foodDesc,
            'foodPrice': $scope.foodPrice,
            'menuId': $scope.menuId
        }).then(function(data, status){
            $scope.init();
        })
    };  
});
</script>