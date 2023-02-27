"use strict";var CalendarList=[],primaryColor="#5A8DEE",primaryLight="#E2ECFF",secondaryColor="#475F7B",secondaryLight="#E6EAEE",successColor="#39DA8A",successLight="#D2FFE8",dangercolor="#FF5B5C",dangerLight="#FFDEDE",warningColor="#FDAC41",warningLight="#FFEED9",infoColor="#00CFDD",infoLight="#CCF5F8 ",lightColor="#b3c0ce",veryLightBlue="#e7edf3",cloudyBlue="#b3c0ce";function CalendarInfo(){this.id=null,this.name=null,this.checked=!0,this.color=null,this.bgColor=null,this.borderColor=null}function addCalendar(r){CalendarList.push(r)}function findCalendar(r){var o;return CalendarList.forEach((function(a){a.id===r&&(o=a)})),o||CalendarList[0]}!function(){var r,o=0;o+=1,(r=new CalendarInfo).id=String(o),r.name="My Calendar",r.color=infoColor,r.bgColor=infoLight,r.dragBgColor=infoColor,r.borderColor=infoColor,addCalendar(r),o+=1,(r=new CalendarInfo).id=String(o),r.name="Company",r.color=primaryColor,r.bgColor=primaryLight,r.dragBgColor=primaryColor,r.borderColor=primaryColor,addCalendar(r),o+=1,(r=new CalendarInfo).id=String(o),r.name="Family",r.color=secondaryColor,r.bgColor=secondaryLight,r.dragBgColor=secondaryColor,r.borderColor=secondaryColor,addCalendar(r),o+=1,(r=new CalendarInfo).id=String(o),r.name="Friend",r.color=successColor,r.bgColor=successLight,r.dragBgColor=successColor,r.borderColor=successColor,addCalendar(r),o+=1,(r=new CalendarInfo).id=String(o),r.name="Travel",r.color=warningColor,r.bgColor=warningLight,r.dragBgColor=warningColor,r.borderColor=warningColor,addCalendar(r),o+=1,(r=new CalendarInfo).id=String(o),r.name="etc",r.color=secondaryColor,r.bgColor=cloudyBlue,r.dragBgColor=secondaryLight,r.borderColor=cloudyBlue,addCalendar(r),o+=1,(r=new CalendarInfo).id=String(o),r.name="Birthdays",r.color=dangercolor,r.bgColor=dangerLight,r.dragBgColor=dangerLight,r.borderColor=dangercolor,addCalendar(r),o+=1,(r=new CalendarInfo).id=String(o),r.name="Holidays",r.color=primaryColor,r.bgColor=veryLightBlue,r.dragBgColor=veryLightBlue,r.borderColor=primaryLight,addCalendar(r)}();
