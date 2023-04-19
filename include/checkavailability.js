//define variables
const daysTag = document.querySelector(".days");
const currentDate = document.querySelector(".current-date");
const prevNextIcons = document.querySelectorAll(".icons span");
let date = new Date();
let currYear = date.getFullYear();
let currMonth = date.getMonth();
const months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
var events = [];

//function to add events
const addEvent = (eventName, startDate, endDate) => 
{
  events.push({eventName, startDate, endDate});
};

//function to render the calendar
const renderCalendar = () => 
{
  let firstDayOfMonth = new Date(currYear, currMonth, 1).getDay();
  let lastDateOfMonth = new Date(currYear, currMonth + 1, 0).getDate();
  let lastDayOfMonth = new Date(currYear, currMonth, lastDateOfMonth).getDay();
  let lastDateOfLastMonth = new Date(currYear, currMonth, 0).getDate();

  let liTag = ""; //initlize string for li elemetns

  //days of previous month on the page
  for (let i = firstDayOfMonth; i > 0; i--) 
  {
    liTag += `<li class="inactive"><div class="date">${lastDateOfLastMonth - i + 1}</div></li>`;
  }

  //current month days
  for (let i = 1; i <= lastDateOfMonth; i++) 
  {
    let isToday =
      i === date.getDate() &&
      currMonth === new Date().getMonth() &&
      currYear === new Date().getFullYear()
        ? "active"
        : "";

    let eventHtml = ""; //hold html of events

    //check for events using start and end date
    events.forEach((event) => 
    {
      const eventStart = new Date
      (
        event.startDate.getFullYear(),
        event.startDate.getMonth(),
        event.startDate.getDate()
      );

      const eventEnd = new Date
      (
        event.endDate.getFullYear(),
        event.endDate.getMonth(),
        event.endDate.getDate()
      );

      const eventLength = (eventEnd - eventStart) / (1000 * 60 * 60 * 24); //calculate event lengnth

      //display events on the current month and year
      for (let j = 0; j <= eventLength; j++) 
      {
        const eventDate = new Date
        (
          eventStart.getFullYear(),
          eventStart.getMonth(),
          eventStart.getDate() + j
        );

        if 
        (
          eventDate.getDate() === i &&
          eventDate.getMonth() === currMonth &&
          eventDate.getFullYear() === currYear 
        ) 
        {
          eventHtml += `<div class="event">${event.eventName}</div>`;
        }
      }
    });

    liTag += `<li class="${isToday}">${i}${eventHtml}</li>`;
  }

  //days of next month on the page
  for (let i = lastDayOfMonth; i < 6; i++) 
  {
    liTag += `<li class="inactive">${i - lastDayOfMonth + 1}</li>`;
  }

  currentDate.innerText = `${months[currMonth]} ${currYear}`; //set inner text to current year and month
  daysTag.innerHTML = liTag; //set inner html ot days of current month
};

//add all car reservations as events on calendar
for(let i=0; i < reservations.length; i++)
{
  addEvent(reservations[i].CustomerName, new Date(reservations[i].PickUpDate), new Date(reservations[i].DropOffDate));
};

renderCalendar();

//loop over all the icons
prevNextIcons.forEach(icon => 
{
  //when click
  icon.addEventListener("click", () => 
  {
    //change current month
    currMonth = icon.id === "prev" ? currMonth - 1 : currMonth + 1;
    
    //if year is changed
    if (currMonth < 0 || currMonth > 11)
    {
      date = new Date(currYear, currMonth, new Date().getDate());
      currYear = date.getFullYear();
      currMonth = date.getMonth();
    } 
    else
    {
      date = new Date();
    }
    renderCalendar();
  });
});
