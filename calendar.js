//this will run once html is initialized

var today = new Date();
var currYear = today.getFullYear();
var currMonth = today.getMonth();
var calMonth = new Month(currYear, currMonth); //initial value is today

var currDay = today.getDate(); //mke today "active"
var months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
var daysPerMonth = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 31, 31];

document.addEventListener("DOMContentLoaded", buildCalender, false);
document.getElementById("prevMonth").addEventListener("click", monthBack, false);
document.getElementById("nextMonth").addEventListener("click", monthFwd, false);

var user;

if(document.getElementById("login")){
  document.getElementById("login").addEventListener("click", loginAjax, false); // Bind the AJAX call to button click
}
if(document.getElementById("addUser")){
  document.getElementById("addUser").addEventListener("click", addUser, false);
}
if(document.getElementById("addEvent")){
  document.getElementById("addEvent").addEventListener("click", addEvent, true);
}


//MAKE THE CALENDAR
function buildCalender(){
  console.log("hi");
  document.getElementById("calendarView").innerHTML=""; //remove old cal

  //IF USER IS NOT LOGGED IN PRINT THIS
  document.getElementById("monthTitle").innerHTML=months[calMonth.month]+" - "+calMonth.year;

  var daysInMonth = daysPerMonth[calMonth.month];
  var calTable = document.getElementById('calendarView');
  var day1Date = new Date(calMonth.year, calMonth.month, 1);
  var dayOfWeek = day1Date.getDay(); //0...6, this can tell us how many days to skip in cal
  var squaresToSkip=dayOfWeek;

  //LEAP YEARS Wheeeeee
  if (calMonth.month==1 && calMonth.year%4==0){
    daysInMonth=daysInMonth+1;
  }

  var day=1;
  var week=1;
  var daysOfWeek = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];
  var daysOfWeekRow = calTable.insertRow(0);
  for (var d in daysOfWeek){
    var weekday = daysOfWeekRow.insertCell(d);
    weekday.innerHTML = daysOfWeek[d];
  }

  var weekElement = calTable.insertRow(week); //week 1
  var skipCounter=0;
  while (day<=daysInMonth){
    var dateToEnter = new Date(calMonth.year, calMonth.month, day);
    console.log(squaresToSkip);
    while (squaresToSkip>0){
      var emptyDay=weekElement.insertCell(skipCounter);
      squaresToSkip=squaresToSkip-1;
      skipCounter=skipCounter+1;
    }
    if (dateToEnter.getDay()===0 && day!=1){ //sunday
      week=week+1;
      weekElement=calTable.insertRow(week);
      var inserted = weekElement.insertCell(dateToEnter.getDay());
      inserted.innerHTML=day;
    }
    else{
      var inserted = weekElement.insertCell(dateToEnter.getDay());
      inserted.innerHTML=day;
    }
    day=day+1;

  }

}

//NEXT
function monthFwd(){
  //may not need
  calMonth=calMonth.nextMonth();
  buildCalender();

//BACK
}
function monthBack(){
  calMonth=calMonth.prevMonth();
  buildCalender();
}


//ADD USER
function addUser(event){
  "use strict";
  var username = document.getElementById("user").value;
  var password = document.getElementById("pass").value;

  var logInInfo = "regUsername="+encodeUIRComponent(username)+"&regPassword="+encodeUIRComponent(password);
  var xmlHttp = new XMLHttpRequest();
  xmlHttp.open("POST", "registerUser.php", true);
  xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  xmlHttp.addEventListener("load", validateUserReg, false);
  xmlHttp.send(logInInfo);

}

//VALIDATE new user
function validateUserReg(event){
  "use strict";
  var jsonData = JSON.parse(event.target.responseText); // parse the JSON into a JavaScript object
  document.getElementById("user").value='';
  document.getElementById("pass").value='';
  alert(jsonData.message);
}

//ADD EVENTS
function addEvent(event){

  var eventData = {
    eventName: document.getElementById("title").value,
    eventDate: document.getElementById("eventDate").value ,
    eventType: document.getElementById("eventType").value,
  };

  console.log(eventData);
  /*
  fetch('http://ec2-18-221-53-132.us-east-2.compute.amazonaws.com/~roboRobi/addEvent.php', {
    method: 'POST',
    mode:"cors",
    headers: new Headers({ "Content-Type":"application/x-form-urlencoded" }),
    body: eventData,
  }).then(function(resp){
    if (!resp.ok) {
      throw Error(resp.statusText);
    }
    return resp.text();
  }).then(function(data){
    console.log(data);
  }).catch(function(error){
    console.error(error);
  });*/


  //Get request and data is passed in a URL (post request passead as object not in url)

  var eventInfo = "&eventName="+encodeURIComponent(eventData.eventName)+"&eventDate="+encodeURIComponent(eventData.eventDate);
  var xmlHttp = new XMLHttpRequest();
  xmlHttp.open("POST", "addEvent.php", true);
  xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  xmlHttp.addEventListener("load", validateEvent, false);
  xmlHttp.send(eventInfo);

}

//VALIDATE event
function validateEvent(event){
  "use strict";
  //console.log(event.target.responseText);

  var jsonData = JSON.parse(event.target.responseText); // parse the JSON into a JavaScript object
  //THIS IS THE DATE
  console.log(jsonData);

  console.log(jsonData.date.split("T")[0]);
  //THIS IS THE TIME
  console.log(jsonData.date.split("T")[1]);
  document.getElementById("title").value= '';
  document.getElementById("eventDate").value = '';
  document.getElementById("eventTime").value= '';
  document.getElementById("eventType").value= '';
  alert(jsonData.message);


}

//ADD EVENTS
function deleteEvent(event){
  "use strict";
  var eventName = document.getElementById("title").value;
  var eventDate = document.getElementById("eventDate").value;
  var eventType = document.getElementByClass("eventType").value;

  var eventInfo = "&eventName="+encodeUIRComponent(eventName)+"&eventDate="+encodeUIRComponent(eventDate)+"&eventTime="+encodeUIRComponent(eventTime)+"&eventType="+encodeUIRComponent(eventType);
  var xmlHttp = new XMLHttpRequest();
  xmlHttp.open("POST", "deleteEvent.php", true);
  xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  xmlHttp.addEventListener("load", validateDeleteEvent, false);
  xmlHttp.send(eventInfo);
}

//VALIDATE event
function validateDeleteEvent(event){
  "use strict";
  var jsonData = JSON.parse(event.target.responseText); // parse the JSON into a JavaScript object
  document.getElementById("title").value= '';
  document.getElementById("eventDate").value = '';
  document.getElementById("eventTime").value= '';
  document.getElementById("eventType").value= '';
  alert(jsonData.message);
}

//load events into caleder from getMonthEvents.php

function loadEvents() {
  var currM = calMonth.month;
  var currY = calMonth.year;
  var eType  = document.getElementById("eventType").value;

  $.post("getMonthEvents.php", {user: user, month: currM, year: currY, type: eType}).done(
    function addIt(thedata){
    var data = JSON.parse(thedata);
    console.log(data);
    for (var i in data){
      addEventsToCal(data[i]);
    }
  }
  }).fail(function() { alert( "error" );  });

}

function addEventsToCal(jsonDate){
  //where we could make the event data then visible in the calender by updating the document elements
}
