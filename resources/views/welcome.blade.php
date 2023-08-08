<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Maesa Grow</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/reset-css@5.0.1/reset.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+3:wght@900&display=swap" rel="stylesheet">
    <style>
        :root {
            --white: #000000;
            --gray-light: #e99393;
            --background: #ffffff;
            --blue: #282b70;
            --blue-light: #34368c;
            --yellow: #ffdf38;
        }

        body {
            background: var(--background);
            color: var(--white);
            display: flex;
            align-items: center;
            flex-wrap: inherit;
            height: 100vh;
            justify-content: center;
            font-family: "Source Sans 3", sans-serif !important;
            position: relative;
        }

        #inner {
            padding: 2rem;
            display: grid;
            position: relative;
            z-index: 2;
            grid-template-columns: 1fr auto;
        }

        h1 {
            font-size: 4.5rem;
            font-family: "Source Sans 3", sans-serif;
            font-weight: 900;
            padding: 1rem 0.3rem;
            text-transform: uppercase;
        }

        #svg-container {
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
            width: 100%;
            height: 100%;
        }
    </style>
</head>
{{-- https://play.google.com/store/apps/details?id=com.fauzighozali.mgamobile&hl=en --}}

<body>
    <section id="inner">
        <h1>Maesa Grow</h1><br>
        <center>
            <h2>v2 - 08/08/2023</h2><br>
            <div>
                <a href="https://play.google.com/store/apps/details?id=com.fauzighozali.mgamobile&hl=en"
                    target="_blank">
                    <img src="https://www.cdnlogo.com/logos/g/24/googleplay.svg" width="200" height="200">
                </a>
            </div>
        </center>
    </section>
    <svg id="svg-container"></svg>
</body>

<script>
    // globals
    const strengthMultiplier = 0.05;
    const angleMultiplier = -0.505;
    const velocityMultiplier = 0.005;

    // Get the SVG container element
    const svgContainer = document.getElementById("svg-container");

    // Get the window dimensions
    const windowWidth = window.innerWidth;
    const windowHeight = window.innerHeight;

    // Create circles using the custom array
    const circles = [{
            r: 10,
            x: windowWidth * 0.9,
            y: windowHeight * 0.5,
            color: "var(--white)",
            border: "transparent"
        },
        {
            r: 20,
            x: windowWidth * 0.89,
            y: windowHeight * 0.5,
            color: "var(--white)",
            border: "transparent"
        },
        {
            r: 30,
            x: windowWidth * 0.88,
            y: windowHeight * 0.5,
            color: "var(--gray-light)",
            border: "transparent"
        },
        {
            r: 120,
            x: windowWidth * 0.1,
            y: windowHeight * 0.9,
            color: "var(--blue-light)",
            border: "transparent"
        },
        {
            r: 300,
            x: windowWidth * 0.6,
            y: windowHeight * 0.4,
            color: "transparent",
            border: "var(--yellow)"
        }
    ];

    // Create an array to store circle objects
    const circleObjects = [];

    // Create circles from the array and store the circle objects
    circles.forEach((circle) => {
        const circleObject = {
            element: createCircle(
                circle.r,
                circle.x,
                circle.y,
                circle.color,
                circle.border
            ),
            velocity: {
                x: 0,
                y: 0
            },
            acceleration: {
                x: 0,
                y: 0
            }
        };
        circleObjects.push(circleObject);
        svgContainer.appendChild(circleObject.element);
    });

    // Initialize velocities and accelerations for each circle
    circleObjects.forEach((circle, index) => {
        circle.velocity = {
            x: 0,
            y: 0
        };
        circle.acceleration = {
            x: 0,
            y: 0
        };
    });

    // Friction to control the speed decay
    const friction = 0.01;

    // Variable to store the last clicked position, initialized with the center of the DOM
    let lastClickPosition = {
        x: windowWidth / 2,
        y: windowHeight / 2
    };

    function createCircle(radius, x, y, color, borderColor) {
        const circle = document.createElementNS(
            "http://www.w3.org/2000/svg",
            "circle"
        );
        circle.setAttribute("r", radius);
        circle.setAttribute("cx", x);
        circle.setAttribute("cy", y);
        circle.setAttribute("fill", color);
        circle.setAttribute("stroke", borderColor);
        return circle;
    }

    function moveCircles() {
        const mouseX = lastClickPosition.x;
        const mouseY = lastClickPosition.y;

        // Move each circle and update its velocity and acceleration
        circleObjects.forEach((circle) => {
            circle.acceleration = applyAttraction(circle, mouseX, mouseY);
            circle = moveCircle(circle);
        });

        requestAnimationFrame(moveCircles);
    }

    function applyAttraction(circle, mouseX, mouseY) {
        const currentX = parseFloat(circle.element.getAttribute("cx"));
        const currentY = parseFloat(circle.element.getAttribute("cy"));

        const attractionStrength = strengthMultiplier; // The strength of attraction

        const dx = mouseX - currentX;
        const dy = mouseY - currentY;

        const angle = Math.atan2(dy, dx);
        const clockwiseAngle = angle + Math.PI /
            angleMultiplier; // Add a rotation to make te direction of circles anti-clockwise

        const ax = Math.cos(clockwiseAngle) * attractionStrength;
        const ay = Math.sin(clockwiseAngle) * attractionStrength;

        // Update the acceleration of the circle
        return {
            x: ax,
            y: ay
        };
    }

    function moveCircle(circle, velocity, acceleration) {
        const currentX = parseFloat(circle.element.getAttribute("cx"));
        const currentY = parseFloat(circle.element.getAttribute("cy"));

        const deltaX = circle.velocity.x;
        const deltaY = circle.velocity.y;

        const newAcceleration = {
            x: deltaX * friction,
            y: deltaY * friction
        };

        const newVelocity = {
            x: circle.velocity.x +
                circle.acceleration.x * circle.element.getAttribute("r") * velocityMultiplier,
            y: circle.velocity.y +
                circle.acceleration.y * circle.element.getAttribute("r") * velocityMultiplier
        };

        const newX = currentX + newVelocity.x;
        const newY = currentY + newVelocity.y;

        circle.element.setAttribute("cx", newX);
        circle.element.setAttribute("cy", newY);

        // Update the position, velocity, and acceleration of the circle
        circle.element.setAttribute("cx", newX);
        circle.element.setAttribute("cy", newY);

        circle.velocity = newVelocity;
        circle.acceleration = newAcceleration;

        return circle;
    }

    // Update last clicked position based on mouse or touch event
    function updateLastClickPosition(event) {
        lastClickPosition.x =
            event.clientX || (event.touches[0] && event.touches[0].clientX);
        lastClickPosition.y =
            event.clientY || (event.touches[0] && event.touches[0].clientY);
    }

    // Add event listeners for mousedown, mousemove, touchstart, and touchmove events
    window.addEventListener("mousedown", updateLastClickPosition);
    window.addEventListener("mousemove", updateLastClickPosition);
    window.addEventListener("touchstart", updateLastClickPosition);
    window.addEventListener("touchmove", updateLastClickPosition);

    // Start the animation loop
    moveCircles();
</script>

</html>
