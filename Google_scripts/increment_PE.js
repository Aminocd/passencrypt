function onFormSubmit(e) {

Utilities.sleep(1000);
  
var sheet = SpreadsheetApp.getActiveSheet();
var row =  SpreadsheetApp.getActiveSheet().getLastRow();

var incrementCount = sheet.getRange("A2").getValue();
  
incrementCount++;

sheet.getRange(row,1).setValue(incrementCount);
sheet.getRange("A2").setValue(incrementCount);

}
