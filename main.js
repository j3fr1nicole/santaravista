const menuBtn = document.getElementById("menu-btn");
const navLinks = document.getElementById("nav-links");
const menuBtnIcon = menuBtn.querySelector("i");

menuBtn.addEventListener("click", (e) => {
  navLinks.classList.toggle("open");

  const isOpen = navLinks.classList.contains("open");
  menuBtnIcon.setAttribute("class", isOpen ? "ri-close-line" : "ri-menu-line");
});

navLinks.addEventListener("click", (e) => {
  navLinks.classList.remove("open");
  menuBtnIcon.setAttribute("class", "ri-menu-line");
});

const scrollRevealOption = {
  origin: "bottom",
  distance: "50px",
  duration: 1000,
};

ScrollReveal().reveal(".header__image img", {
  ...scrollRevealOption,
  origin: "right",
});
ScrollReveal().reveal(".header__content p", {
  ...scrollRevealOption,
  delay: 500,
});
ScrollReveal().reveal(".header__content h1", {
  ...scrollRevealOption,
  delay: 1000,
});
ScrollReveal().reveal(".header__btns", {
  ...scrollRevealOption,
  delay: 1500,
});

ScrollReveal().reveal(".destination__card", {
  ...scrollRevealOption,
  interval: 500,
});

ScrollReveal().reveal(".showcase__image img", {
  ...scrollRevealOption,
  origin: "left",
});
ScrollReveal().reveal(".showcase__content h4", {
  ...scrollRevealOption,
  delay: 500,
});
ScrollReveal().reveal(".showcase__content p", {
  ...scrollRevealOption,
  delay: 1000,
});
ScrollReveal().reveal(".showcase__btn", {
  ...scrollRevealOption,
  delay: 1500,
});

ScrollReveal().reveal(".banner__card", {
  ...scrollRevealOption,
  interval: 500,
});

ScrollReveal().reveal(".discover__card", {
  ...scrollRevealOption,
  interval: 500,
});

const swiper = new Swiper(".swiper", {
  slidesPerView: 3,
  spaceBetween: 20,
  loop: true,
});

// Automatic image changing logic (tambahan baru)
const images = ["assets/Serenity in Bali_ Finding Peace at Top Yoga Retreats.jpg", "assets/Bandung West Java - Indonesia.jpg", "assets/Taman Bunga Nusantara Cipanas.jpg"];

let currentIndex = 0;

function changeImage() {
  const imageElement = document.querySelector(".showcase__image img");
  imageElement.classList.add("hidden"); // tambahkan efek fade-out

  setTimeout(() => {
    currentIndex = (currentIndex + 1) % images.length;
    imageElement.src = images[currentIndex];
    imageElement.classList.remove("hidden"); // tambahkan efek fade-in
  }, 1000); // 1 detik untuk transisi fade-out
}

// Set interval untuk mengganti gambar setiap 3 detik
setInterval(changeImage, 3000);

// Scroll Reveal Animation for Services Section
document.addEventListener("DOMContentLoaded", function () {
  const services = document.querySelectorAll(".service__card");

  services.forEach((service, index) => {
    service.style.opacity = 0;
    service.style.transform = "translateY(20px)";
    setTimeout(() => {
      service.style.transition = "all 0.6s ease-out";
      service.style.opacity = 1;
      service.style.transform = "translateY(0)";
    }, index * 150); // Add a slight delay for staggered animation
  });
});

//trip plan
function redirectToWhatsApp(event) {
  event.preventDefault(); // Mencegah form langsung melakukan submit

  // Ambil data dari form
  const form = document.getElementById("tripForm");
  const name = form.name.value;
  const email = form.email.value;
  const destination = form.destination.value;
  const people = form.people.value;
  const car = form.car.value;
  const tripStartDate = form.tripStartDate.value;
  const tripEndDate = form.tripEndDate.value;
  const message = form.message.value;

  // URL WhatsApp dengan pesan yang diformat
  const whatsappNumber = "6281111170403"; // Ganti dengan nomor WhatsApp Anda
  const whatsappMessage = `Hello, my name is ${name}. I would like to book a trip to ${destination} for ${people} people and $car seats car. 
Arrival: ${tripStartDate}, Departure: ${tripEndDate}. Additional message: ${message}`;
  const whatsappUrl = `https://wa.me/${whatsappNumber}?text=${encodeURIComponent(whatsappMessage)}`;

  // Submit form ke PHP untuk menyimpan data
  form.submit();

  // Redirect ke WhatsApp
  setTimeout(() => {
    window.location.href = whatsappUrl;
  }, 500); // Delay untuk memastikan PHP memproses data sebelum redirect
}

// Submit upload proof

document.addEventListener("DOMContentLoaded", function () {
  const form = document.getElementById("uploadProofForm");

  form.addEventListener("submit", function (event) {
    event.preventDefault(); // Mencegah reload halaman default

    const formData = new FormData(form);

    fetch("uploadProof.php", {
      method: "POST",
      body: formData,
    })
      .then((response) => response.text())
      .then((result) => {
        alert("Upload berhasil! Anda akan diarahkan ke halaman utama.");
        window.location.href = "index.html"; // Ganti dengan halaman utama Anda
      })
      .catch((error) => {
        alert("Terjadi kesalahan, silakan coba lagi.");
        console.error("Error:", error);
      });
  });
});
