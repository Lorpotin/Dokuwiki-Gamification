$(document).ready(function()
{
   //$("body").hide(0).delay(100).fadeIn(500)
   var top10array = [];

   $(document).on("click", "#updDb", function() 
   {
      $.ajax(
      {
         type: "POST",
         url: "logToJson.php",
         success: function(html)
         {    
            console.log(html);
         }
      });
   });

   $(document).on("click", "#homeBtn", function() 
   {
      window.location = "index.php";
   });

   $(document).on("click", "#loginBtn", function() 
   {
      window.location = "loginpage.php";
   });

   $(document).on("click", "#regBtn", function() 
   {
      window.location = "registerpage.php";
   });

   $(document).on("focusout", "#registerName", function()
   {
      validateUserName();
   });

   $(document).on("click", "#register", function()
   {
      registerUser();
   });

   $(document).on("click", "#login", function()
   {
      login();
   });

   $(document).on("click", "#logoutBtn", function()
   {
      logout();
   });

   $(document).on("click", "#profileBtn", function()
   {
      window.location = "profilepage.php";
   });

   $(document).on("click", "#goToRegisterPage", function()
   {
      window.location = "registerpage.php";
   });

   $(document).on("click", "#learnMore", function()
   {
      $('html,body').animate(
      {
        scrollTop: $(".infos").offset().top
      },
        'slow');
   });

   $(document).on("click", "#testButton", function()
   {
      testFunction();
   });

   $(document).on("click", "#1monthBtn", function()
   {
      updateGraph("one");
   });

   $(document).on("click", "#3monthBtn", function()
   {
      updateGraph("two");
   });

   $(document).on("click", "#12monthBtn", function()
   {
      updateGraph("three");
   });
   $(document).on("click", "#settingsBtn", function()
   {
      togglePersonalSettings();
   });
   $(document).on("click", "#saveButton", function()
   {
      updatePersonalSettings();   
   });

   $(document).on("click", "#notificationBtn", function()
   {
      getFriendRequests(); 
   });


   $(document).on("click", "#profileBadge", function()
   {
      var badgeID = $(this).attr('uid');
      achievementDescriptionPopUp(badgeID);
   });

  
   $(document).on("click", "#acceptBtn", function()
   {
      //retrieve correct name and uid from the script that retrieves friend requests -> send them to acceptRequest, so PHP knows which request you are accepting
      var name = $(this).attr('name');
      var uid = $(this).attr('uid');
      acceptRequest(name, uid);
   });

   $(document).on("click", "#cancelButton", function()
   {
      $("#hiddenSettings").css("visibility","hidden").hide().fadeIn(200);
      document.getElementById("settingsToggle").style.visibility = "visible";
      document.getElementById("myInfo1").style.visibility = "visible";  
   });
   

   $(document).on("click", "#submitmsg", function() 
   {
      clientMsg = $("#usermsg").val();
      var action = "chat";
      $.ajax({
         
         type: "POST",
         url: "ajax_handler.php",
         data: "postaction="+action+"&message="+clientMsg,
         success: function(html)
         {
            document.getElementById('usermsg').value='';
         }
      });
   });

   /* START 3rd party notification plugin functions START */

   function LogOutStickyToast() 
   {
      $().toastmessage('showToast', {
         text     : 'Successfully logged out!',
         sticky   :  false,
         stayTime :  1000,
         position : 'top-right',
         type     : 'success',
         closeText: '',
         close    : function () 
         {
            window.location = "homepage.php";
         }
      });
   }
   function LogInStickyToast() 
   {
      $().toastmessage('showToast', {
         text     : 'Successfully logged in!',
         sticky   :  false,
         stayTime :  1000,
         position : 'top-right',
         type     : 'success',
         closeText: '',
         close    : function () 
         {
            window.location = "homepage.php";
         }
      });
   }

   function achievementDescriptionPopUp(badgeID)
   {
      text = "";

      if(badgeID == 1)
      {
         text = "1st edit";
      }
      if(badgeID == 2)
      {
         text = "10 edits";
      }
      if(badgeID == 3)
      {
         text = "X edits";
      }
      if(badgeID == 4)
      {
         text = "X edits";
      }
      if(badgeID == 8)
      {
         text = "X edits";
      }
      if(badgeID == 9)
      {
         text = "X edits";
      }

      $().toastmessage('showToast', {
         text     :  text,
         sticky   :  false,
         stayTime :  750,
         position : 'top-center',
         type     : 'notice',
         closeText: '',
         close    : function () 
         {
            //window.location = "homepage.php";
         }
      });
   }

   /* END 3rd party notification plugin functions END */
      
   function updatePersonalSettings()
   {
      $("#hiddenSettings").css("visibility","hidden").hide().fadeIn(100);
      //document.getElementById("hiddenSettings").style.visibility = "hidden";
      document.getElementById("settingsToggle").style.visibility = "visible";
      document.getElementById("myInfo1").style.visibility = "visible";
      var firstname = $("#firstname").val();
      var lastname = $("#lastname").val();
      var mail = $("#mail").val();
      var phone = $("#phone").val();
      var description = $("#desc").val();
      console.log(lastname);
      var action = "updatePersonalSettings";
      $.ajax(
      {
         type: "POST",
         url: "ajax_handler.php",
         data: "postaction="+action+"&firstname="+firstname+"&lastname="+lastname+"&mail="+mail+"&phone="+phone+"&desc="+description,
         success: function(returnData)
         {
            window.location = "profilepage.php";
         }
      });
   }
   
   function togglePersonalSettings()
   {
      $("#hiddenSettings").css("visibility","visible").hide().fadeIn(200);
      document.getElementById("settingsToggle").style.visibility = "hidden";
      document.getElementById("myInfo1").style.visibility = "hidden";
   }

   //Get all users and their points in the database, to be used in a general top-X list in the front page
   function getAllPoints()
   {
      var action = "getAllPoints";
      $.ajax(
      {
         type: "GET",
         url: "ajax_handler.php",
         data: "getaction="+action,
         dataType: "json",
         success: function(returnData)
         {
            top10array = returnData;
            for(var i = 0; i < returnData.length; i++)
            {
               $(".top10list").append("<li class='top10'><a href=user.php?u="+ returnData[i].username + ">"+ returnData[i].username + "</a> "+ returnData[i].score +" points</li>").hide().fadeIn(300);
            }
         }
      });
   }

   function getProfilePicture()
   {
      var action = "getProfilePicture";
      $.ajax(
      {
         type: "GET",
         url: "ajax_handler.php",
         data: "getaction="+action,
         success: function(returnData)
         {
            $("#welcome").append("<img class='profilePicClass' src=" +returnData+ ">").hide().fadeIn(300);
         }
      });
   }
   //Use graphApp.php to handle drawing all graphs. Work maybe on the loading animation in the future, commented out for now
   function getWikiActivity()
   {
      var action = "getWikiActivity";
      var period = "one";
      $.ajax(
      {
         type: "GET",
         url: "graphApp.php",
         data: "getaction="+action+"&timePeriod="+period,
         /*beforeSend: function()
         {
            $(".loader").show();
         },*/
         success: function(returnData)
         {
            console.log(returnData);
            //$('.loader').hide();
            //$("#graphId").fadeIn(function() {
            //Lets append the desired image to graphId div. Next we need to hide the image, in order to fade it back in. If we fade it in without hiding it first, it just blinks
            //Because the animation fires before the image is actually loaded into the div.
            $("#graphId").append("<img class='graphClass' src=" +returnData+ ">").hide().fadeIn(500);
            //});
         }
      });
   }

   

   function getFriendRequests()
   {
      $(".notificationDisplay").empty();
      var action = "getFriendRequests";
      var dataString = "getaction="+action;
      $.ajax(
      {
         type: "GET",
         url: "ajax_handler.php",
         data: dataString,
         dataType: "json",
         success: function(returnData)
         {
            var arr = [];
            for(var x in returnData)
            {
               arr.push(returnData[x]);
            }
            $(".notificationDisplay").append("<p>Your friend requests!</p>");
            for(var i = 0; i < arr.length; i++)
            {
               $(".notificationDisplay").append("<p class='notificationResults' >"+arr[i]['user1']+"</p> <button uid="+arr[i]['id']+" name="+arr[i]['user1']+" id='acceptBtn' class='button-primary'>Accept</button>");
            }
         }
      });
   }

   function acceptRequest(name, uid)
   {
      var action = "accept";
      $.ajax(
      {
         type: "POST",
         url: "ajax_handler.php",
         data: "postaction="+action+"&name="+name+"&uid="+uid,
         success: function(returnData)
         {
            console.log(returnData);
            if(returnData == "\r\nacceptOK")
            {
               $(".notificationDisplay").append("<p> Accepted!</p>");
               $("#acceptBtn").hide().fadeOut(300);
            }
         }
      });
   }

   function declineRequest()
   {
      var action = "decline";
      $.ajax(
      {
         type: "POST",
         url: "ajax_handler.php",
         data: "getaction="+action,
         success: function(returnData)
         {

         }
      });
   }

   /*|||||||||||||||||||||||--- START OF SEARCH BAR CODE SECTION ---||||||||||||||||||||||||||||*/

   $(".searchBar").keyup(function() {
      $(".searchDisplay").empty();
      var action = "search";
      var searchbox = $(this).val();
      var dataString = "searchword="+ searchbox +"&postaction="+action;
      if(searchbox == " ")
      {

      }
      else
      {
         $.ajax(
         {
            type: "POST",
            url: "ajax_handler.php",
            data: dataString,
            dataType: "json",
            success: function(returnData)
            {
               
               var arr = [];
               for(var x in returnData)
               {
                  arr.push(returnData[x]);
               }
               for(var i = 0; i < arr.length; i++)
               {
                  $(".searchDisplay").append("<a class='searchResults' href=user.php?u="+arr[i]['username']+">"+arr[i]['username']+"</a><br>");
               }
            }
         });
      } 
   });
   
      /*$(document).mousedown(function (e)
      {
            var container = $(".display");
            if (!container.is(e.target) && container.has(e.target).length === 0)
            {
                container.hide();
            }
      });*/

   /*|||||||||||||||||||||||--- END OF SEARCH BAR CODE SECTION ---||||||||||||||||||||||||||||*/

   

  
   /*|||||||||||||||||||||||--- START OF DRAG AND DROP CODE SECTION ---||||||||||||||||||||||||||||*/

   // getElementById
   function $id(id) 
   {
      return document.getElementById(id);
   }

   // initialize drag and drop
   function Init() {

      var filedrag = $id("filedrag");

      // is XHR2 available?
      var xhr = new XMLHttpRequest();
      if (xhr.upload) 
      {
         // file drop
         filedrag.addEventListener("dragover", FileDragHover, false);
         filedrag.addEventListener("dragleave", FileDragHover, false);
         filedrag.addEventListener("drop", FileSelectHandler, false);
         filedrag.style.display = "block";
        
      }
   }

   function FileDragHover(e) 
   {
      e.stopPropagation();
      e.preventDefault();
      e.target.className = (e.type == "dragover" ? "hover" : "");
   }

   function FileSelectHandler(e) 
   {

      // cancel event and hover styling
      FileDragHover(e);

      // fetch FileList object
      var files = e.target.files || e.dataTransfer.files;
      console.log(files);
 
      var uploadForm = new FormData($("#yourregularuploadformId")[0]); 

      // process all File objects
      for (var i = 0, f; f = files[i]; i++) 
      {
         uploadForm.append("fileToUpload", files[i]);
         UploadFile(uploadForm);
      }

   }
   //Just for testing & debugging
   function ParseFile(file) 
   {
      console.log(file.name);
      console.log(file.type);
      console.log(file.size);
   }

   function UploadFile(file)
   {
      console.log(file);
      var action = "upload";
      $.ajax({
         url: "savePicture.php",
         type: "POST",
         processData: false, 
         contentType: false, 
         cache: false,
         //"postaction="+action+"&
         data: file,
         success: function(returnData)
         {
            //location.reload();
            console.log(returnData);
         }
      });
   }

   /*|||||||||||||||||||||||--- END OF DRAG AND DROP CODE SECTION ---||||||||||||||||||||||||||||*/

   function getPlayersOnlineNow()
   {
      var action = "getOnlineUsers";

      $.ajax(
      {
         type: "GET",
         url: "ajax_handler.php",
         data: "getaction="+action,
         dataType: "json",
         success: function(returnData)
         {
            if(returnData.length > 0)
            {
               $("#onlineHeader").html("People currently online");   
            }
            for(var i = 0; i < returnData.length; i++)
            {
               $("#onlineList").append("<li class='onlineUsers'>"+ returnData[i].username+"</li>");
            }

         }
      });
   }

   //Use graphApp.php to handle drawing all graphs. Work maybe on the loading animation in the future, commented out for now
   //Used for updating graph with different information
   function updateGraph(asd)
   {
      var action = "getWikiActivity";
      var period = asd;
      console.log(asd);
      $.ajax(
      {
         type: "GET",
         url: "graphApp.php",
         data: "getaction="+action+"&timePeriod="+period,
         /*beforeSend: function()
         {
            $(".loader").show();
         },*/
         success: function(returnData)
         {
            console.log(returnData);
            //$('.loader').hide();
            $("#graphId").fadeOut(function() {
               $("#graphId").fadeIn(function() {
                  $("#graphId").html("<img class='graphClass' src="+returnData+">");
               });
            });
            
         }
      });
   }

   function updateChat()
   {
      var oldscrollHeight = $("#chatbox").prop("scrollHeight") - 20;
      $.ajax({
         url: "chatLog.html",
         cache: false,
         success: function(data)
         {
            $("#chatbox").html(data);
            var newscrollHeight = $("#chatbox").prop("scrollHeight") - 20;
            if(newscrollHeight > oldscrollHeight){
               $("#chatbox").animate({ scrollTop: newscrollHeight }, 'normal');
            }
         },
      });
   }


   //Used for validating usernames in registering a new account. Username needs to match to dokuwiki. Lets make this dynamic!
   function validateUserName()
   {
      var action = "validateUserName";
      var name = $("#registerName").val();
      $.ajax(
      {
         type: "GET",
         url: "ajax_handler.php",
         data: "getaction="+action+"&name="+name,
         //dataType: "json",
         success: function(returnData)
         {
            console.log(returnData);
            if(returnData == true)
            {
               $("#registerName").css("border-color", "green");
               document.getElementById("greentick").style.display = "inline";
               document.getElementById("registerLabel").innerHTML = "Username was found!";
             
            }
            else if(returnData == false)
            {
               $("#registerName").css("border-color", "red");
               document.getElementById("greentick").style.display = "none";
               document.getElementById("registerLabel").innerHTML = "Username was not found!";
             
            }
            else
            {
               document.getElementById("greentick").style.display = "none";
               document.getElementById("registerLabel").innerHTML = "Username - must be the same as you have in dokuwiki";
              
            }
         } 
      });
   }

   function registerUser()
   {
      var action = "register";
      var name = $("#registerName").val();
      var password = $("#registerPw").val();
      var confirmationPw = $("#confirmationPw").val();
      $.ajax(
      {
         type: "POST",
         url: "ajax_handler.php",
         data: "postaction="+action+"&name="+name+"&password="+password+"&confirmationPw="+confirmationPw,
         //dataType: "json",
         success: function(returnData)
         {
            console.log(returnData);
            if(returnData == "empty")
            {
               $("#registerPw").css("border-color", "red");
               $("#confirmationPw").css("border-color", "red");
               document.getElementById("confLabel").innerHTML = "Password cant be empty!";
            }

            else if(returnData == "usernameError")
            {
               $("#registerName").css("border-color", "red");
               document.getElementById("greentick").style.display = "none";
               document.getElementById("registerLabel").innerHTML = "Username was not found!!";
            }

            else if(returnData == "match")
            {
               $("#registerPw").css("border-color", "red");
               $("#confirmationPw").css("border-color", "red");
               document.getElementById("confLabel").innerHTML = "Passwords must match!";
            }
            else if(returnData == "success")
            {
               alert("Account created!");
            }
            else if(returnData == "accountfound")
            {
               alert("You already have an account "+name+ "!");
            }
         } 
      });   
   }

   function testFunction()
   {
      $.ajax(
      {
         type: "POST",
         url: "cronJob.php",
         //dataType: "json",
         success: function(returnData)
         {
            console.log(returnData);
         }
      }); 
   }

   function getAchievements()
   {
      var action = "getAchievements";
      $.ajax(
      {
         type: "GET",
         url: "ajax_handler.php",
         data: "getaction="+action,
         dataType: "json",
         success: function(returnData)
         {
            for(var i = 0; i < returnData.length; i++)
            {
               $("#achHeader").append("<img id='profileBadge' uid="+returnData[i].achievementID+" class='badgeClass' src=images/Achievements/Badge"+returnData[i].achievementID+".png>").hide().fadeIn(500);
            }
         }
      }); 
   }

  

   function login()
   {
      var action = "login";
      var name = $("#loginName").val();
      var password = $("#loginPw").val();
      $.ajax(
      {
         type: "POST",
         url: "ajax_handler.php",
         data: "postaction="+action+"&name="+name+"&password="+password,
         //dataType: "json",
         success: function(returnData)
         {
            if(returnData == "emptyUser")
            {
               $("#loginName").css("border-color", "red");
               document.getElementById("confLabelName").innerHTML = "Username cant be empty!";
            }

            else if(returnData == "emptyPw")
            {
               $("#loginPw").css("border-color", "red");
               document.getElementById("confLabelPw").innerHTML = "Password cant be empty!";
            }

            else if(returnData == true)
            {
               $("#loginPw").css("border-color", "green");
               $("#loginName").css("border-color", "green");
               document.getElementById("confLabelName").innerHTML = "Username";
               document.getElementById("confLabelPw").innerHTML = "Password";
               LogInStickyToast();
            }
            else
            {
               console.log(returnData);
               $("#loginPw").css("border-color", "red");
               $("#loginName").css("border-color", "red");
               document.getElementById("confLabelName").innerHTML = "Wrong username or password!";
               document.getElementById("confLabelPw").innerHTML = "Wrong username or password!";
            }
          
         } 
      });   
   }
   function logout()
   {
      var action = "logout";
      $.ajax(
      {
         type: "POST",
         url: "ajax_handler.php",
         data: "postaction="+action,
         //dataType: "json",
         success: function(returnData)
         {
            if(returnData == true)
            {
               
               LogOutStickyToast();
            }
            else
            {
               console.log(returnData);
            }
         }
      });
   }
   //Either place in an independent javascript file, or only run when in homepage so the function doesn't run when not needed.
   if($(location).attr('pathname') == "/dokuwiki/Gamification/homepage.php")
   {
      getAllPoints();
   }
   //Place in own js file or run when needed like above?
   if($(location).attr('pathname') == "/dokuwiki/Gamification/homepage.php")
   {
      getWikiActivity();
   }
   //Place in own js file or run when needed like above?
   if($(location).attr('pathname') == "/dokuwiki/Gamification/homepage.php")
   {
      getPlayersOnlineNow();
   }
   if($(location).attr('pathname') == "/dokuwiki/Gamification/homepage.php")
   {
      setInterval(function(){ updateChat(); }, 2000);
   }
   //$$ window.File && window.FileList && window.FileReader maybe check?
   if($(location).attr('pathname') == "/dokuwiki/Gamification/profilepage.php" )
   {
      Init();
      getProfilePicture();
      getAchievements();
   }

});