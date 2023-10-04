function clicc(){
    let v = document.querySelector("#vid div");
    let div_vid = document.getElementById("vid");

    if(v===null){

        let didi = document.createElement("div");
        let video = document.createElement("video");
        let source = document.createElement("source");
        source.setAttribute("src","assets/videos/biologie.mp4");
        source.setAttribute("type","video/mp4");
        video.setAttribute("autoplay","true");
        video.setAttribute("controls","true");
        video.append(source);

        didi.append(video);
        div_vid.append(didi);
    }                    
    
    else{
        
        div_vid.removeChild(v);
    }

}