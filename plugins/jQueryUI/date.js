$(function(){
  $("body").on("click", ".datepicker", function(){
    $(this).datepicker(
        {
          changeMonth: true,
          changeYear: true,
          //maxDate: "getDate()",
          setDate: "myServerDate",
          //yearRange: "2000:'+(new Date).getFullYear()",
          dateFormat: "dd/mm/yy"
        }
    );
    $(this).datepicker("show");
  });
});




//date is equal and greater then current date
$( function() {
    $( ".datepicker1" ).datepicker({
        changeMonth: true,
        changeYear: true,
        setDate: "myServerDate",
        //maxDate: "getDate()",
        //yearRange: "1900:'+(new Date).getFullYear()",
        dateFormat: "dd-mm-yy"
    });
    function getDate( element ) {
        var date;
        try {
            date = $.datepicker.parseDate( dateFormat, element.value );
        } catch( error ) {
            date = null;
        }
        return date;
    }
} );

//date is equal and less then current date
$( function() {
    $( ".datepickerToday" ).datepicker({
        inline: true,
        showOtherMonths: true,
        dayNamesMin: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
        changeMonth: true,
        changeYear: true,
        maxDate: "getDate()",
        setDate: "myServerDate",
       // yearRange: "2000:'+(new Date).getFullYear()",
        dateFormat: "dd/mm/yy"
    });
    function getDate( element ) {
        var date;
        try {
            date = $.datepicker.parseDate( dateFormat, element.value );
        } catch( error ) {
            date = null;
        }
        return date;
    }
} );



//date is equal and greater then current date
$( function() {
    $( ".datepickerGreater" ).datepicker({
        changeMonth: true,
        changeYear: true,
        minDate: "getDate()",
        setDate: "myServerDate",
        //yearRange: "2000:'+(new Date).getFullYear()",
        dateFormat: "dd/mm/yy"
    });
    function getDate( element ) {
        var date;
        try {
            date = $.datepicker.parseDate( dateFormat, element.value );
        } catch( error ) {
            date = null;
        }
        return date;
    }
} );



/**max date ////from date  < to date ///**/
$( function() {
    var dateFormat = "dd/mm/yy",
        from = $( "#from" )
            .datepicker({
                inline: true,
                showOtherMonths: true,
                dayNamesMin: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
                //defaultDate: "+1w",
                changeMonth: true,
                changeYear: true,
               // yearRange: "2000:'+(new Date).getFullYear()",
                numberOfMonths: 1,
                maxDate: "getDate()",
                setDate: "myServerDate",
                dateFormat: "dd/mm/yy",
            })
            .on( "change", function() {
                to.datepicker( "option", "minDate", getDate( this ) );
            }),
        to = $( "#to" ).datepicker({
            //defaultDate: "+1w",
            inline: true,
            showOtherMonths: true,
            dayNamesMin: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
            changeMonth: true,
            changeYear: true,
           // yearRange: "2000:'+(new Date).getFullYear()",
            numberOfMonths: 1,
            maxDate: "getDate()",
            setDate: "myServerDate",
            dateFormat: "dd/mm/yy",
        })
            .on( "change", function() {
                from.datepicker( "option", "maxDate", getDate( this ) );
            });

    function getDate( element ) {
        var date;
        try {
            date = $.datepicker.parseDate( dateFormat, element.value );
        } catch( error ) {
            date = null;
        }
        return date;
    }
} );



