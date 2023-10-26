

let faceapi;
let detections = [];

let video;
let canvas;

let nbSecNeutre = 0;
let nbSecContent = 0;
let nbSecEnerve = 0;
let nbSecStresse = 0;
let nbSecSurpris = 0;

var sommetimeneutre = 0;
var Newsommetimeneutre =0;

var sommetimecontent = 0;
var Newsommetimecontent =0;

var sommetimeenerve = 0;
var Newsommetimeenerve =0;

var sommetimetriste = 0;
var Newsommetimetriste =0;

var sommetimedegout = 0;
var Newsommetimedegout =0;

var sommetimesurpris = 0;
var Newsommetimesurpris =0;

var sommetimepeur = 0;
var Newsommetimepeur =0;


function setup() {
  canvas = createCanvas(480, 360);
  canvas.id("canvas");

  video = createCapture(VIDEO);// Creat the video: ビデオオブジェクトを作る
  video.id("video");
  video.size(width, height);

  const faceOptions = {
    withLandmarks: true,
    withExpressions: true,
    withDescriptors: true,
    minConfidence: 0.5
  };

  //Initialize the model: モデルの初期化
  faceapi = ml5.faceApi(video, faceOptions, faceReady);
}

function faceReady() {
  faceapi.detect(gotFaces);// Start detecting faces: 顔認識開始
}

// Got faces: 顔を検知
function gotFaces(error, result) {
  if (error) {
    console.log(error);
    return;
  }

  detections = result;　//Now all the data in this detections: 全ての検知されたデータがこのdetectionの中に
  // console.log(detections);

  clear();//Draw transparent background;: 透明の背景を描く
  drawBoxs(detections);//Draw detection box: 顔の周りの四角の描画
  drawLandmarks(detections);//// Draw all the face points: 全ての顔のポイントの描画
  drawExpressions(detections, 20, 250, 14);//Draw face expression: 表情の描画

  faceapi.detect(gotFaces);// Call the function again at here: 認識実行の関数をここでまた呼び出す
}

function drawBoxs(detections){
  if (detections.length > 0) {//If at least 1 face is detected: もし1つ以上の顔が検知されていたら
    for (f=0; f < detections.length; f++){
      let {_x, _y, _width, _height} = detections[f].alignedRect._box;
      stroke(44, 169, 225);
      strokeWeight(1);
      noFill();
      rect(_x, _y, _width, _height);
    }
  }
}

function drawLandmarks(detections){
  if (detections.length > 0) {//If at least 1 face is detected: もし1つ以上の顔が検知されていたら
    for (f=0; f < detections.length; f++){
      let points = detections[f].landmarks.positions;
      for (let i = 0; i < points.length; i++) {
        stroke(44, 169, 225);
        strokeWeight(3);
        point(points[i]._x, points[i]._y);
      }
    }
  }
}

function drawExpressions(detections, x, y, textYSpace){

  var startTimeNeutre = performance.now()
  var startTimeContent = performance.now()
  var startTimeEnerve = performance.now()
  var startTimeTriste = performance.now()
  var startTimeDegoute = performance.now()
  var startTimeSurpris = performance.now()
  var startTimePeur = performance.now()

  if(detections.length > 0){//If at least 1 face is detected: もし1つ以上の顔が検知されていたら
    let {neutral, happy, angry, sad, disgusted, surprised, fearful} = detections[0].expressions;
    textFont('Helvetica Neue');
    textSize(14);
    noStroke();
    fill(44, 169, 225);

    text("neutre:       " + nf(neutral*100, 2, 2)+"%", x, y);
    text("content: " + nf(happy*100, 2, 2)+"%", x, y+textYSpace);
    text("enerve:        " + nf(angry*100, 2, 2)+"%", x, y+textYSpace*2);
    text("triste:            "+ nf(sad*100, 2, 2)+"%", x, y+textYSpace*3);
    text("degout: " + nf(disgusted*100, 2, 2)+"%", x, y+textYSpace*4);
    text("surpris:  " + nf(surprised*100, 2, 2)+"%", x, y+textYSpace*5);
    text("peur:           " + nf(fearful*100, 2, 2)+"%", x, y+textYSpace*6);

    
    

    if(nf(neutral*100, 2, 2)>0.90)
    {
      
      var endTimeNeutre=performance.now()
    }

    else if(nf(happy*100, 2, 2)>0.90)
    {
      var endTimeContent=performance.now()
      //nbSecContent+=1;
    }
    else if(nf(angry*100, 2, 2)>0.90)
    {
      var endTimeEnerve=performance.now()
      //nbSecEnerve+=1;
    }
    else if(nf(fearful*100, 2, 2)>0.90)
    {
      var endTimeTriste=performance.now()
      //nbSecStresse+=1;
    }
    else if(nf(disgusted*100, 2, 2)>0.90)
    {
      var endTimeDegoute=performance.now()
      //nbSecSurpris+=1;
    }
    else if(nf(surprised*100, 2, 2)>0.90)
    {
      var endTimeSurpris=performance.now()
      //nbSecStresse+=1;
    }
    else if(nf(fearful*100, 2, 2)>0.90)
    {
      var endTimePeur=performance.now()
      //nbSecSurpris+=1;
    }
    
    
   
  }else{//If no faces is detected: 顔が1つも検知されていなかったら
    text("neutre: ", x, y);
    text("content: ", x, y + textYSpace);
    text("enerve: ", x, y + textYSpace*2);
    text("triste: ", x, y + textYSpace*3);
    text("degoute: ", x, y + textYSpace*4);
    text("surpris: ", x, y + textYSpace*5);
    text("peur: ", x, y + textYSpace*6);
  }
  

  sommetimeneutre = endTimeNeutre-startTimeNeutre;
  Newsommetimeneutre=Newsommetimeneutre+sommetimeneutre;

  sommetimecontent = endTimeContent-startTimeContent;
  Newsommetimecontent=Newsommetimecontent+sommetimecontent;

  sommetimeenerve = endTimeEnerve-startTimeEnerve;
  Newsommetimeenerve=Newsommetimeenerve+sommetimeenerve;

  sommetimetriste = endTimeTriste-startTimeTriste;
  Newsommetimetriste=Newsommetimetriste+sommetimetriste;

  sommetimedegout = endTimeDegoute-startTimeDegoute;
  Newsommetimedegout=Newsommetimedegout+sommetimedegout;

  sommetimesurpris = endTimeSurpris-startTimeSurpris;
  Newsommetimesurpris=Newsommetimesurpris+sommetimesurpris;

  sommetimepeur = endTimePeur-startTimePeur;
  Newsommetimepeur=Newsommetimepeur+sommetimepeur;
  
  
  document.getElementById("testNeutre").innerText=Newsommetimeneutre;
  document.getElementById("testContent").innerText=Newsommetimecontent;
  document.getElementById("testEnerve").innerText=Newsommetimeenerve;
  document.getElementById("testTriste").innerText=Newsommetimetriste;
  document.getElementById("testDegoute").innerText=Newsommetimedegout;
  document.getElementById("testSurpris").innerText=Newsommetimesurpris;
  document.getElementById("testPeur").innerText=Newsommetimepeur;
  console.log(`neutre pour ${Newsommetimeneutre} millisecondes`)
  //console.log("content pour "+nbSecContent/100+" secondes");
  
}


