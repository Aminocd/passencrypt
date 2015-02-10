function truncateTable() {
    // call PHP file with fetch() method
  
  var url = "http://kianoo.com/passencrypt/truncatetable.php";
  
  var response = UrlFetchApp.fetch(url);

  Logger.log(response);
}
               
