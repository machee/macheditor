require.config({
  paths: {
    'intercom': 'vendor/intercom-machee/intercom.amd',
    'domReady': 'vendor/requirejs-domready/domReady',
  }
});

require(
    ['domReady', 'intercom', 'config', 'ace/ace', 'shared/files'],
    function (domReady, intercom, config, ace) { domReady(function ()
{
    var editor = ace.edit("editor");
    editor.getSession().setMode("ace/mode/"+config.aceMode);
    editor.setTheme("ace/theme/vibrant_ink");
    
    var viewport = document.getElementById("viewport");

    document.getElementById("editor").addEventListener('touchstart', function(event) {
        viewport.content = "user-scalable=0";
    });
    
    document.body.addEventListener('touchmove', function(event) {
        viewport.content = "user-scalable=1";
    });

    intercom = intercom.getInstance();

    var save = document.getElementById("save");
    
    save.onclick = function() 
    {
        save.innerHTML = "Saving";
        var path   = config.basePath+"api/"+config.project+"/"+config.file;

        var request = new XMLHttpRequest();
        request.onload = function() {
            var response = JSON.parse(this.responseText);
            
            if (this.status != 200) {
                console.log(response);
                alert("status not 200, check console - CTRL-SHIFT-J");
            } else if (response.new) {
                intercom.emit("newFile", response);
            }
            
            save.innerHTML = "Save";
        };
        
        request.onerror = function() {
            alert("request.onerror: not sure how to log");
        };
        request.open("put", path, true);
        request.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        request.setRequestHeader(
            "Content-type","application/x-www-form-urlencoded");
        request.send("content="+encodeURIComponent(editor.getValue()));
    };

})});
