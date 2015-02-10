function sendHttpPostAll_PE() {


// get values in last row

  var ss = SpreadsheetApp.getActiveSpreadsheet();
  var sheet = ss.getSheets()[0];

  // position of last row:

  var lastRow = sheet.getLastRow();

  var firstRow = lastRow-4;

  // Logger.log(firstRow);

  var currentRow = firstRow;

  // loop from first to last row

  while (currentRow != lastRow+1) {
  // send currentRow values to php file

    // range of current row:

  var range = sheet.getRange(currentRow,1,1,12);

    // get values of curent row range

  var values = range.getValues();

    // create payload object
  var payload =
      {
        "FullName" : values[0][2],
        "income" : values[0][6],
      };

  // create object used as fetch() function parameter containing options

  var options =
      {
        "method" : "post",
        "payload" : payload
      };

   // send data with fetch() method

  var url = "http://kianoo.com/passencrypt/checkname.php";

  var response = UrlFetchApp.fetch(url, options);

  Logger.log(response);

    if (response == 1) {
       var payload =
      {
        "FullName" : values[0][2],
        "Age" : values[0][3],
        "Gender" : values[0][4],
        "married" : values[0][5],
        "income" : values[0][6],
        "EducationYears" : values[0][7],
        "Agesp" : values[0][8],
        "incomesp" : values[0][9],
        "EducationYearssp" : values[0][10],
      };

      var options =  {
        "method" : "post",
        "payload" : payload
      };


      var url = "http://kianoo.com/passencrypt/inputdata.php";

      UrlFetchApp.fetch(url, options);
    }

  Utilities.sleep(1000);

  currentRow++;
  }

}
