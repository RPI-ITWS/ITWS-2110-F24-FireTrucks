function getInfo(type, article, section) {
   $.ajax({
      type: "GET",
      url: "interpretation.json",
      dataType: "json",
      success: function (responseData, status) {
         //responseData[articles/amendments][article/amendment index].sections[section index].interpretation
         let output = responseData[type][article-1].sections[section-1].interpretation;
         $('#interpretationContent').html(output);
      }, error: function (msg) {
         // there was a problem
         alert("There was a problem: " + msg.status + " " + msg.statusText);
      }
   });
}