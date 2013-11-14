require.config({
  paths: {
    'intercom': '../vendor/intercom-machee/intercom.amd',
    'domReady': '../vendor/requirejs-domready/domReady',
  }
});

require(
    ['domReady', 'config', 'intercom'],
    function (domReady, config, intercom) { domReady(function ()
{
    intercom = intercom.getInstance();

    var directories = {};

    var dirnames = document.getElementsByClassName("dirname");
    var count = dirnames.length;
    var dirnamesonclick = function(event) {
        if (event.button !== 0) {
            return;
        }
        
        var file     = event.target.dataset.file;
        var newState = 
            event.target.nextElementSibling.style.display!="block";

        var openDirs = localStorage.openDirectories;

        if (typeof openDirs === "undefined") {
            openDirs = [];
        } else {
            openDirs = JSON.parse(openDirs);
            if (!openDirs instanceof Array) {
                openDirs = [];
            }
        }

        var index = openDirs.indexOf(file);
        if (newState && index < 0) {
            openDirs.push(file);
        } else if (!newState && index >= 0) {
            openDirs.splice(index, 1);
        }

        localStorage.openDirectories = JSON.stringify(openDirs);

        intercom.emit("dirSetDisplay", {
            dir:   file,
            state: newState
        });
    };

    for (var i = 0; i < count; i++) {
        dirnames[i].onmouseup = dirnamesonclick;
        directories[dirnames[i].dataset.file] = 
            dirnames[i].nextElementSibling;
    }
    
    var openDirs = localStorage.openDirectories;
    if (typeof openDirs !== "undefined") {
        openDirs = JSON.parse(openDirs);
        if (openDirs instanceof Array) {
            var dirElement;
            
            count = openDirs.length;
            for (i = 0; i < count; i++) {
                dirElement = directories[openDirs[i]];
                if (dirElement) {
                    dirElement.style.display = "block";
                }
            }
        }
    }
    
    intercom.on("newFile", function(data) {
        console.log(data);
    });
    
    intercom.on("dirSetDisplay", function(data) {
        if (data.dir in directories) {
            directories[data.dir].style.display = (data.state?"block":"none");
        }
    });
    
    var menu = document.getElementById("menu");

    menu.onmouseup = function() {
        menu.style.display = "none";
    };
    
    var contextFile = "";

    var clickContext = function(event) {
        if (event.button !== 0) {
            return;
        }
        
        var file = event.target.nextElementSibling;

        var pos = file.getBoundingClientRect();
        
        menu.style.left = pos.left+"px";
        menu.style.top  = pos.bottom+"px";
        menu.style.display = "block";
        
        if ("file" in file.dataset) {
            contextFile = file.dataset.file;
        } else {
            contextFile = "";
            if (file.parentNode.previousElementSibling !== null) {
                contextFile =
                    file.parentNode.previousElementSibling.dataset.file + "/";
            }
            contextFile += file.innerHTML;
        }
        
        document.getElementById("dirOptions").style.display = 
            file.className == "dirname" ? "block" : "none";
    };
    
    document.getElementById("delete").onmouseup = function(event) {
        if (event.button !== 0) {
            return;
        }
        
        if(confirm("Delete "+contextFile+"?")) {
            var request = new XMLHttpRequest();
            request.onload = function() {
                var response = JSON.parse(this.responseText);
                
                if (this.status != 200) {
                    console.log(response);
                    alert("status not 200, check console - CTRL-SHIFT-J");
                } else if (response.new) {
                    intercom.emit("deleted", response);
                }
            };
            
            request.onerror = function() {
                alert("request.onerror: not sure how to log");
            };
            request.open(
                "delete", 
                config.basePath+"api/"+config.project+"/"+contextFile, 
                true
            );
            request.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
            request.send();
        }
    };
    
    document.getElementById("rename").onmouseup = function(event) {
        if (event.button !== 0) {
            return;
        }
        
        console.log(prompt("New name/location for "+contextFile, contextFile));
    };
    
    document.getElementById("copy").onmouseup = function(event) {
        if (event.button !== 0) {
            return;
        }
        
        console.log(prompt("Name/location for copy of "+contextFile, contextFile));
    };
    
    var contexts = document.getElementsByClassName("context");
    count = contexts.length;
    for (i = 0; i< count; i++) {
        contexts[i].onmouseup = clickContext;
    }

})});
