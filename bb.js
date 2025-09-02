// Import the functions you need from the SDKs you need
import { initializeApp } from "firebase/app";
import { getAnalytics } from "firebase/analytics";
// TODO: Add SDKs for Firebase products that you want to use
// https://firebase.google.com/docs/web/setup#available-libraries

// Your web app's Firebase configuration
// For Firebase JS SDK v7.20.0 and later, measurementId is optional
const firebaseConfig = {
  apiKey: "AIzaSyCriRG_gR8FZ_fsGOTKwj5PQqQukqPzWYU",
  authDomain: "register-8367e.firebaseapp.com",
  projectId: "register-8367e",
  storageBucket: "register-8367e.firebasestorage.app",
  messagingSenderId: "246176273820",
  appId: "1:246176273820:web:1852043c398aa74bc7a459",
  measurementId: "G-PPHWB8PJW7"
};

// Initialize Firebase
const app = initializeApp(firebaseConfig);
const analytics = getAnalytics(app);