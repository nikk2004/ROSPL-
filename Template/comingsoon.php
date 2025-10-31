<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Coming Soon - Mobile Shopee</title>

<style>
/* Full-screen dark gradient background */
body {
    margin: 0;
    padding: 0;
    height: 100vh;
    background: linear-gradient(135deg, #1a1a2e, #162447, #1f4068);
    display: flex;
    justify-content: center;
    align-items: center;
    font-family: "Poppins", sans-serif;
    color: #fff;
}

/* Frosted glass container with shadow */
.container {
    background: rgba(255, 255, 255, 0.05);
    backdrop-filter: blur(15px);
    border-radius: 25px;
    text-align: center;
    padding: 50px 30px;
    width: 90%;
    max-width: 450px;
    box-shadow: 0 15px 40px rgba(0,0,0,0.6);
    animation: fadeIn 1.2s ease-in-out;
}

/* Product image */
.container img {
    width: 180px;
    border-radius: 20px;
    margin-bottom: 25px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.5);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.container img:hover {
    transform: scale(1.1);
    box-shadow: 0 15px 35px rgba(0,0,0,0.7);
}

/* Headings */
h1 {
    font-size: 2.5rem;
    margin-bottom: 15px;
    background: linear-gradient(90deg, #ff9a9e, #fad0c4);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    font-weight: 700;
}

/* Description */
p {
    font-size: 1.1rem;
    color: #dcdcdc;
    margin-bottom: 20px;
}

/* Price */
.price {
    font-size: 1.4rem;
    font-weight: 600;
    color: #ffd700;
    margin-bottom: 25px;
}

/* Countdown with glowing effect */
.countdown {
    font-size: 1.15rem;
    letter-spacing: 1px;
    padding: 12px 20px;
    border-radius: 12px;
    background: rgba(255,255,255,0.1);
    box-shadow: 0 0 15px rgba(255,215,0,0.4);
    margin-bottom: 30px;
    display: inline-block;
}

/* Notify button with gradient & glow */
.btn {
    display: inline-block;
    background: linear-gradient(45deg, #ff6a00, #ee0979);
    color: #fff;
    text-decoration: none;
    font-weight: 600;
    padding: 14px 35px;
    border-radius: 50px;
    transition: all 0.3s ease;
    box-shadow: 0 6px 20px rgba(238,9,121,0.6);
}
.btn:hover {
    transform: scale(1.05);
    box-shadow: 0 8px 25px rgba(255,105,0,0.8);
}

/* Fade-in animation */
@keyframes fadeIn {
    from {opacity: 0; transform: translateY(30px);}
    to {opacity: 1; transform: translateY(0);}
}
</style>
</head>
<body>

<div class="container">
    <img src="../HTML Template/assets/products/ad.jpeg" alt="OnePlus 15">
    <h1>🚀 iPhone 16 Pro</h1>
    <p>The future of smartphones is almost here.<br>Experience innovation like never before.</p>
    <div class="price">Expected Price: ₹1,49,999</div>
    <div id="countdown" class="countdown">Launching in: -- days -- hrs -- mins -- secs</div>
    <a href="index.php" class="btn">🔔 Notify Me</a>
</div>

<script>
// Launch countdown script
const launchDate = new Date("Nov 20, 2025 10:00:00").getTime();

const timer = setInterval(function() {
    const now = new Date().getTime();
    const distance = launchDate - now;

    const days = Math.floor(distance / (1000 * 60 * 60 * 24));
    const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    const mins = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
    const secs = Math.floor((distance % (1000 * 60)) / 1000);

    document.getElementById("countdown").innerHTML =
        `Launching in: ${days}d ${hours}h ${mins}m ${secs}s`;

    if (distance < 0) {
        clearInterval(timer);
        document.getElementById("countdown").innerHTML = "🎉 iPhone 16 Pro is now available!";
    }
}, 1000);
</script>

</body>
</html>
