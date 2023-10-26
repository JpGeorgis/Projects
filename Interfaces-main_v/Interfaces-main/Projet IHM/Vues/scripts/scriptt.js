const video=document.getElementById('videoElement')
function startvideo() {

    navigator.getUserMedia(
{ video: {} },
stream => video.srcObject = stream,

err => console.error(err)
    )
}
 startvideo()