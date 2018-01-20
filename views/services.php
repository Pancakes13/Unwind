<?php
require ("../header.php");
include '../sidebar.php';
?>
 <!-- Content Wrapper. Contains page content -->
 <div class="content-wrapper">
  <section class="content" ng-app="unwindApp">
    <div ng-cloak ng-controller="floorController" data-ng-init="init()">
      <md-content>
        
        <md-tabs md-dynamic-height md-border-bottom>
          <md-tab label="Service List">
            <md-content>
                <div>
                    <md-button class="md-raised" style="color:white; background-color:green" data-target="#insertService" data-toggle="modal">Add Service</md-button>
                </div>

                <div id="insertService" class="modal fade" role="dialog" ng-hide="serviceModal">
                    <div class="modal-dialog">
                        <form ng-submit="createService()">
                            <div class="modal-content">
                                <div class="modal-header" style="background-color:#003300; color:white;">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h2>Create Service</h2>
                                </div>
                                <div class="modal-body">
                                    <input class="form-control" placeholder="Service Name" ng-model="serviceName" required>
                                    <select class="form-control" ng-model="serviceType" required>
                                        <option value="" hidden disabled selected>Choose Service Type</option>
                                        <option value="Cleaning">Cleaning</option>
                                        <option value="Restock">Restock</option>
                                    </select>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-success" onclick="$('#insertService').modal('hide');">Create Service <span class="fa fa-check"></span></button>
                                    <button type="button" class="btn btn-danger" onclick="$('#insertService').modal('hide');">Close <span class="fa fa-close"></span></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div ng-cloak layout-gt-sm="row" layout="column">
                
    <div flex-gt-sm="50" flex >

        <md-toolbar layout="row" class="md-hue-3">
        <div class="md-toolbar-tools">
            <span>Cleaning</span>
        </div>
        </md-toolbar>

        <md-content>
            <md-list flex>
                <md-list-item class="md-3-line rrList" ng-repeat = "x in serviceList track by $index" ng-click="null" ng-if="x.ServiceType=='Cleaning'" style="border: 1px solid grey;">
                    <div>
                        <div>{{x.ServiceName}}</div>
                    </div>  
                </md-list-item>
            </md-list>
        </md-content>
    </div>
    <md-divider></md-divider>
    <div flex-gt-sm="50" flex>

        <md-toolbar layout="row" class="md-hue-3">
        <div class="md-toolbar-tools">
            <span>Restock</span>
        </div>
        </md-toolbar>

        <md-content>
            <md-list flex>
                <md-list-item class="md-3-line rrListB" ng-repeat = "x in serviceList track by $index" ng-click="null" ng-if="x.ServiceType=='Restock'" style="border: 1px solid grey;">
                    <div>
                        <div>{{x.ServiceName}}</div>
                    </div>  
                </md-list-item>
            </md-list>
        </md-content>
    </div>
                
            </md-content>
          </md-tab>
          <md-tab label="Service Requests">
            <md-content>
              <md-list flex>
                <md-list-item class="md-3-line rrList" ng-click="null" ng-repeat = "x in requestList track by $index">
                  <div>
                    {{x.Name}}
                    <span><small>({{x.ServiceRequestMonth}} {{x.ServiceRequestDay}}, {{x.ServiceRequestYear}})</small></span><br>
                    <strong>{{x.ServiceName}}</strong>
                    ({{x.ServiceType}})<br>
                    <div ng-if="x.ServiceRequestStatus=='Pending'">
                        <button class="btn btn-success" ng-click="acceptServiceRequest(x.ServiceRequestId)">Accept <span class="fa fa-check"></button>
                        <button class="btn btn-danger" ng-click="rejectServiceRequest(x.ServiceRequestId)">Reject <span class="fa fa-close"></button>
                    </div>
                    <div ng-if="x.ServiceRequestStatus=='Waiting'">
                        <button class="btn btn-warning" ng-click="completeServiceRequest(x.ServiceRequestId)">Complete Service Request <span class="fa fa-check-square-o"></span></button>
                    </div>
                  </div>  
                </md-list-item>
              <md-list>            
            </md-content>
          </md-tab>
        </md-tabs>
      </md-content>
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
var active = angular.element( document.querySelector( '#servicesTab' ) );
active.addClass('active');

var app = angular.module('unwindApp', ['ngMaterial', 'oitozero.ngSweetAlert']);

app.controller('floorController', function($scope, $http, $mdDialog, SweetAlert) {
  
    $scope.init = function () {
        $scope.serviceName=$scope.serviceType="";
        $scope.foodSet = {food: []};
        $scope.food = [];

        $http.get("../queries/get/getServices.php").then(function (response){
            $scope.serviceList = response.data.records;
            
        });
        $http.get("../queries/get/getServiceRequest.php").then(function (response){
            $scope.requestList = response.data.records;
            
        });
    };

    $scope.createService = function() {
        $http.post('../queries/insert/insertService.php', {
            'serviceName': $scope.serviceName,
            'serviceType': $scope.serviceType
        }).then(function(data, status){
          $scope.init();
          SweetAlert.swal("Success!", "You Created a Service", "success");
        })
    };

    $scope.acceptServiceRequest = function($id){
        $http.post('../queries/update/updateServiceRequestWaiting.php', {
            'id': $id,
        }).then(function(data, status){
            $scope.init();
        })
    };

    $scope.rejectServiceRequest = function($id){
        $http.post('../queries/update/updateServiceRequestRejected.php', {
            'id': $id,
        }).then(function(data, status){
            $scope.init();
        })
    };

    $scope.completeServiceRequest = function($id){
        $http.post('../queries/update/updateServiceRequestCompleted.php', {
            'id': $id,
        }).then(function(data, status){
            $scope.init();
        })
    };
});
</script>