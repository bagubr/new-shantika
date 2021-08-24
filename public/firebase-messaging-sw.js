importScripts('https://www.gstatic.com/firebasejs/8.10.0/firebase-app.js')
importScripts('https://www.gstatic.com/firebasejs/8.10.0/firebase-messaging.js')
var firebaseConfig = {
    apiKey: "AIzaSyCkXZd29pEyQ4RtkliscrSCwqN2m1QqTuM",
    authDomain: "agen-santika.firebaseapp.com",
    databaseURL: "https://agen-santika-default-rtdb.asia-southeast1.firebasedatabase.app",
    projectId: "agen-santika",
    storageBucket: "agen-santika.appspot.com",
    messagingSenderId: "348893299264",
    appId: "1:348893299264:web:2a519915d231980d3515a5",
    measurementId: "G-1KR74VKTEC"
};
firebase.initializeApp(firebaseConfig);
var messaging = firebase.messaging();