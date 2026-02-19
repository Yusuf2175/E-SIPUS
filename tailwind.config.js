/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    theme: {
        extend: {
            colors: {
                primarys: "#7A1CAC",
                secondrys: "#AD49E1",
                background: "#2E073F",
                cstm: "#EBD3F8",
                ungu: "#7C3AED",
            },
            fontFamily: {
                sans: [
                    "Instrument Sans",
                    "ui-sans-serif",
                    "system-ui",
                    "sans-serif",
                ],
            },
        },
    },
    plugins: [require("daisyui"), require("@tailwindcss/forms")],
    daisyui: {
        themes: ["light", "dark"],
    },
};
