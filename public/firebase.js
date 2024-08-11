<script type="module">
  {/* // Import the functions you need from the SDKs you need */}
  import { initializeApp } from "https://www.gstatic.com/firebasejs/10.12.5/firebase-app.js";
  import { getAnalytics } from "https://www.gstatic.com/firebasejs/10.12.5/firebase-analytics.js";
  {/* // TODO: Add SDKs for Firebase products that you want to use
  // https://firebase.google.com/docs/web/setup#available-libraries */}

  {/* // Your web app's Firebase configuration
  // For Firebase JS SDK v7.20.0 and later, measurementId is optional */}
  const firebaseConfig = {
    apiKey: "AIzaSyD1DZQEc5ME0PTePkmWkDoHjsydN3tAG2k",
    authDomain: "laravel-blog-fdc7a.firebaseapp.com",
    databaseURL: "https://laravel-blog-fdc7a-default-rtdb.firebaseio.com",
    projectId: "laravel-blog-fdc7a",
    storageBucket: "laravel-blog-fdc7a.appspot.com",
    messagingSenderId: "921784986094",
    appId: "1:921784986094:web:15ab6f8ee546b9d88586ff",
    measurementId: "G-D6YGDM6MYR"
  };

  // Initialize Firebase
  const app = initializeApp(firebaseConfig);
  const analytics = getAnalytics(app);
</script>