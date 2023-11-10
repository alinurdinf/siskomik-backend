importScripts('https://www.gstatic.com/firebasejs/8.3.2/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/8.3.2/firebase-messaging.js');
   
firebase.initializeApp({
     apiKey: "AIzaSyDHyTmMWk1vZvPEiHzv6RsMKDUyzaFq-wA",
    projectId: "siskomik-backend",
    messagingSenderId: "976341310694",
    appId: "1:976341310694:web:ffd5619ef0017fa0066f27",
});
  
const messaging = firebase.messaging();
messaging.setBackgroundMessageHandler(function({data:{title,body,icon}}) {
    return self.registration.showNotification(title,{body,icon});
});