<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-80452104-1', 'auto');
  ga('send', 'pageview');

</script>

<script type="text/javascript">
    function initialize() {
        var mapOptions = {
	 
 		  center:{ lat : <?php echo $cherish_item['lat']?>, lng : <?php echo $cherish_item['lng']?>},
          zoom: 12,
          disableDefaultUI: true,
          zoomControl: true          
        };
        
  
        var map = new google.maps.Map(document.getElementById('map-canvas'),
            mapOptions);
		var marker = new google.maps.Marker({
	    	position: map.getCenter(),
			map: map,
			title: 'Click to zoom',
			});	
			marker.setAnimation(google.maps.Animation.BOUNCE);
			window.setTimeout(function(){ marker.setAnimation(null);}, 2222);
			
		
		
		    
		google.maps.event.addListener(map, 'center_changed', function() {
	    // 3 seconds after the center of the map has changed, pan back to the marker.
	    window.setTimeout(function() {
	    	map.panTo(marker.getPosition());
	    	}, 5000);
	  	});
	
	  	google.maps.event.addListener(marker, 'click', function() {
	    	map.setZoom(17);
			map.setCenter(marker.getPosition());
	  	}); 
	  	
      }
      
     google.maps.event.addDomListener(window, 'load', initialize);
     
     
var app = angular.module('myApp', []);
 	
 app.controller('mainCtrl', ['$scope', '$http',function($scope, $http) {
	 
	 var commentCount = 0;
	 var temp = [];
	 var url = "http://www.gieok.xyz/cherish/";
	 
     var url_arr = window.location.href.split('/');
  	 var id = url_arr[4];

//	 var id = url.substring(url.indexOf("cherish/"), url.length).replace(/[^0-9]/g, '');
	 




	 $scope.$on('floral-rise', function(event) {
	 	var request = $http({
		    method: "get",
			url: url.substring(0, url.indexOf("cherish/")) + "json/riseflower/" + id	
		});
			request.success(function (response) {
				alert('헌화를 하였습니다.');
				window.location.reload();
			}).error(function (response) {
				alert("실패, 새로고침 후 다시 해보세요.");
			});	 
	 	
	 });
	 

	 $scope.$on('modal-toggle', function(event) {
	  	$scope.myModal = !$scope.myModal 			
	 });
	 
	 
	 $scope.$on('letter-updated', function(event, data){
		 
		 if(data)
	 	var request = $http({
		    method: "post",
			url: url.substring(0, url.indexOf("cherish/")) + "json/setcomment/" + id ,
		    data: data
		});
			request.success(function (response) {
			 	window.location.reload();
			 	alert(response);
			}).error(function (response) {
				alert("error ," + response )
			});	 

	});
	

	$scope.commentMore = function(){		
	
		var request = $http({
		    method: "get",
			url: url.substring(0, url.indexOf("cherish/")) + "json/getcomment/" + id + "/" + commentCount++,
		});
			request.success(function (response) {
				temp = temp.concat(response.comments);			
				$scope.comments = temp;
			}).error(function (response) {
				alert("추모글 로드 실패 ," + response )
				window.location.reload();
			});	 

	}		

 }]);
   
   


app.controller('modalCtrl', function ($scope) {
	


	
	$scope.setcomment = function () {		
		if(angular.isUndefined($scope.name) || angular.isUndefined($scope.content)){
			alert("내용 또는 이름이 미입력되었습니다.");
			return false;
		}	
		else
		{
			var data = { content: $scope.content.replace(/\n/g, "\\\\n").replace(/\r/g, "\\\\r").replace(/\t/g, "\\\\t").replace(/\"/g, "'"), name: $scope.name.replace(/[+-`~!@#$%^&amp;*|\\\'\";:\/?]/g, '')};		
			$scope.content = undefined;
			$scope.name = undefined;
			$scope.myModal = false;
		}
		$scope.$emit('letter-updated',data);
	 }
		
});



app.controller('regiCtrl', function($scope) {
/*
	 $scope.modal = function () {
		     $scope.$emit('modal-toggle');
		   
	 }
*/
 }); 



 app.filter('newline', function($sce) {
    return function(text) {
        text = text.replace(/\\n/g, '<br/>');
        return $sce.trustAsHtml(text);
        
    }
	});
 
     
</script>




		
		<div class="main" ng-controller="mainCtrl">
			<p style="display: none;">故 <?php echo $cherish_item['name']?>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
			<div class="top"><h1><span class="ago">故 </span><?php echo $cherish_item['name']?></h1></div>
			<div class="container">
				<div class="nav">
					<ol>
						<li><a href="">분향소</a></li>
			<!--		<li><a href="./untitled9.html">장지</a></li>
						<li><a href="#comment-move">추모글</a></li>
						<li><a href="#">사진</a></li>
						<li><a href="./untitled10.html">연혁</a></li>	-->
						<li><a href="">헌화</a></li>
						<li><a href="#comment-move">추모글</a></li>
					</ol>
				</div>

				<div class="content">
					<div class="left">
					<div class="img-bg" ></div>
				
				<?php 
					if(isset($cherish_item['template'])) {
					echo 'NOT NULL'; 
				?>
						<!-- 	NOT NULL인경우  Template + UserImage 이미지로 채움  -->
						<div class="img" style="background-image: url(/Images/Content/Person/<?php echo $cherish_item['picture'] ?>);">
							<div class="watermark"></div>
							<ul class="registry" ng-controller="regiCtrl">
								<li ng-click="$emit('floral-rise')"><h3 >헌화하기</h3></li>
								<li ng-click="$emit('modal-toggle')"><h3 >추모글적기</h3></li>
							</ul>
						</div>
				<?php
					}else{ 
				?>	
									<!-- 	NULL인경우 Full 이미지로 채움  -->		
						<div class="img" style="background-image: url(/Images/Content/Person/<?php echo $cherish_item['picture'] ?>);">
							
							<div class="watermark"></div>
							<ul class="registry" ng-controller="regiCtrl">
								<li ng-click="$emit('floral-rise')"><h3 >헌화하기</h3></li>
								<li ng-click="$emit('modal-toggle')"><h3 >추모글적기</h3></li>
							</ul>
						</div>		
				<?php 
					}
				?>						
<!--
						<div class="img" style="background-image: url(/Images/Content/Person/<?php echo $cherish_item['picture'] ?>);">
							
							<div class="watermark"></div>
							<ul class="registry" ng-controller="regiCtrl">
								<li ng-click="$emit('floral-rise')"><h3 >헌화하기</h3></li>
								<li ng-click="$emit('modal-toggle')"><h3 >추모글적기</h3></li>
							</ul>
						</div>
						
-->
						<ul class="count">
							<li>
								<h3><?php echo $counter['visitorCnt'] ?></h3>
								<p>방문자</p>
							</li>			
							<li>
								<h3><?php echo $counter['letterCnt'] ?></h3>
								<p>추모글</p>
							</li>
							<li>
								<h3><?php echo $counter['flowerCnt'] ?></h3>
								<p>헌화수</p>
							</li>
						</ul>
					</div>
					<div class="center">
						<ul class="map">
							<li class="view"><div id="map-canvas"></div></li>	
							<li class="address-1">
								<h3>장소: <?php echo $cherish_item['location'] ?></h3>
								<h4><?php echo $cherish_item['address3'] ?></h4>
							</li>
							<li class="address-2">
								<h3><?php echo $cherish_item['city'] ?>
									<?php echo $cherish_item['address1'].$cherish_item['address2'] ?>
								</h3>
							</li>	
		<!--					<li class="address-2"><h3>인천광역시 남동구 구월1동 1198</h3></li>			-->		
						</ul>
								
						<div class="center-floral" >
							<h1 class="category">헌화</h1>
							<ol class="floral-tribute" ng-click="$emit('floral-rise')">	
								<li>
									<ul class="laid">
										<li class="chrysanth<?php echo floor($counter['flowerCnt'] * 1/100) ?>"></li>
										<li class="info"><p>삼가 고인의 명복을 빕니다.</p><!--<p>진달래 드림</p>--></li>
									</ul>
								</li>
								<li>
									<ul class="laid">
										<li class="chrysanth<?php echo floor($counter['flowerCnt'] * 1/100) ?>"></li>
										<li class="info"><p class="m_info">삼가 고인의 명복을 빕니다.</p><!--<p>00전자 드림</p>--></li>
									</ul>
								</li>
								<li>
									<ul class="laid">
										<li class="chrysanth<?php echo floor($counter['flowerCnt'] * 1/100) ?>"></li>
										<li class="info"><p>삼가 고인의 명복을 빕니다.</p><!--<p>봉숭아 드림</p>--></li>
									</ul>
								</li>					
								<li class="more">													
									<a >헌화수가 증가할수록 꽃이 더욱 풍성해집니다.<!--◦ ◦ ◦--></a>
								</li>			
							</ol>																	
						</div>
					
						<div class="center-comment" ng-init="commentMore()">
							<a name="comment-move"> <!-- 추모글 이동 -->
							<ul>
								<li><h1 class="comment-logo"></h1></li>
								<li ng-repeat="comments_item in comments"><h2 ng-bind-html="comments_item.content | newline"></h2><p>{{comments_item.writer}} <span>{{comments_item.time}}</span></p></li>
								
								<li class="more"><a href="" ng-click="commentMore()">◦ ◦ ◦</a></li>	
							</ul>
							<br>
						</div>
		
					</div> <!-- center -->
				
				
				
				</div> <!-- content -->
			
				
			</div> <!-- container -->
	


			<div class="modal-container" ng-show="myModal" ng-controller="modalCtrl">
				<div class="modal-content">
					<ul>
						<li><textarea placeholder="여기에 추모글을 남겨주세요." ng-model="content" maxlength="200"></textarea></li>
						<li>
							<div class="left-footer">
								<input type="text" placeholder="이름" ng-model="name" maxlength="14">
							</div>
							<div class="right-footer">
								<p ng-click="setcomment()">등록하기</p>
							</div>
						</li>
					</ul>
					<div class="modal-dismiss"><a style="color: gray" href="" ng-click="$emit('modal-toggle')">x</a></div>
				</div>
			</div>	
		</div> <!-- main -->	
	</body>
</html>