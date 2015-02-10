function trunctLog_PE() {
  
   // call PHP file with fetch() method
  
  var url = "http://kianoo.com/passencrypt/trunclog.php";
  
  var response = UrlFetchApp.fetch(url);

  Logger.log(response);
}
