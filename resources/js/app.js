import "./bootstrap";

import Swiper from "swiper";
import "swiper/css";

const swiper = new Swiper(".swiper", {
    // Optional parameters
    direction: "horizontal",
    loop: true,
    autoplay: {
        delay: 500,
        disableOnInteraction: false,
    },
});
