function myFunction() {
  var sheet = SpreadsheetApp.getActiveSheet();
  var row =  SpreadsheetApp.getActiveSheet().getLastRow();
  var range = sheet.getRange(row,6);

  var phoneNumber = range.getValue();
  
  var prefix = "'";
  
  phoneNumber = prefix.concat(phoneNumber);
  
  range.setValue(phoneNumber);

}

