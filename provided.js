(function(){
  /* Date.prototype.deltaDays(n)
	 *
	 * Returns a Date object n days in the future.
	 */
  Date.prototype.deltaDays=function(c){
    // relies on the Date object to automatically wrap between months for us
    return new Date(this.getFullYear(),this.getMonth(),this.getDate()+c)
  };
  /* Date.prototype.getSunday()
	 *
	 * Returns the Sunday nearest in the past to this date (inclusive)
	 */
  Date.prototype.getSunday=function(){
    return this.deltaDays(-1*this.getDay())
  }
})();
/**Represents a week.
 *
 * Functions (Methods):
 *	.nextWeek() returns a Week object sequentially in the future
 *	.prevWeek() returns a Week object sequentially in the past
 *	.contains(date) returns true if this week's sunday is the same
 *		as date's sunday; false otherwise
 *	.getDates() returns an Array containing 7 Date objects, each representing
 *		one of the seven days in this month
 */

function Week(c){
  this.sunday=c.getSunday();
  this.nextWeek=function(){
    return new Week(this.sunday.deltaDays(7))
  };
  this.prevWeek=function(){
    return new Week(this.sunday.deltaDays(-7))
  };
  this.contains=function(b){
    return this.sunday.valueOf()===b.getSunday().valueOf()
  };
  this.getDates=function(){
    for(var b=[],a=0;7>a;a++)
      b.push(this.sunday.deltaDays(a));
    return b
  }
}
/**Represents a month.
 *
 * Properties:
 *	.year == the year associated with the month
 *	.month == the month number (January = 0)
 *
 * Functions (Methods):
 *	.nextMonth() returns a Month object sequentially in the future
 *	.prevMonth() returns a Month object sequentially in the past
 *	.getDateObject(d) returns a Date object representing the date
 *		d in the month
 *	.getWeeks() returns an Array containing all weeks spanned by the
 *		month; the weeks are represented as Week objects
 */

function Month(c,b){
  this.year=c;
  this.month=b;
  this.nextMonth=function(){
    return new Month(c+Math.floor((b+1)/12),(b+1)%12)
  };
  this.prevMonth=function(){
    return new Month(c+Math.floor((b-1)/12),(b+11)%12)
  };
  this.getDateObject=function(a){
    return new Date(this.year,this.month,a)
  };
  this.getWeeks=function(){
    var a=this.getDateObject(1),b=this.nextMonth().getDateObject(0),c=[],a=new Week(a);
    for(c.push(a);!a.contains(b);)
      a=a.nextWeek(),c.push(a);
    return c
  }
};
