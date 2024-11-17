$(function () {
  $("#calendarbar").daterangepicker(
    {
      singleDatePicker: true,
      showDropdowns: true,
      locale: {
        format: "YYYY/MM/DD",
        applyLabel: "確定",
        cancelLabel: "取消",
        daysOfWeek: ["日", "一", "二", "三", "四", "五", "六"],
        monthNames: [
          "一月",
          "二月",
          "三月",
          "四月",
          "五月",
          "六月",
          "七月",
          "八月",
          "九月",
          "十月",
          "十一月",
          "十二月",
        ],
        firstDay: 1,
      },
      autoUpdateInput: false,
    },
    function (start, end, label) {
      $("#daterange").val(start.format("YYYY/MM/DD"));
      console.log(
        "A new date selection was made: " + start.format("YYYY-MM-DD")
      );
    }
  );

  // Make the calendar icon trigger the date range picker
  $("#calendarbar").on("click", function () {
    $("#daterangepicker").focus();
  });
});
