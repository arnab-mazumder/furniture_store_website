<div class="site-bg-overlay"></div>
<?php include 'includes/header.php'; ?>
<div class="container about-us-container">
    <h1>About Us</h1>
    <div class="about-split">
        <section class="about-intro">
            <h2>Where Comfort Meets Craftsmanship</h2>
            <p>
                At <strong>Furniture Store</strong>, we believe that a home is not just a place—it's a feeling, a journey, and an expression of your unique story. Our mission is to help you fill your home and office with furniture that reflects your personality, adds genuine comfort, and stands the test of time.
            </p>
            <p>
                Our story started with a single question: why should anyone settle for mass-produced furniture that lacks character? From humble beginnings as a dedicated local workshop, we have grown into a trusted name for thousands of families and businesses across the country. Every table, chair, and cabinet in our showroom embodies our founding principle: <em>beautiful spaces begin with genuine craftsmanship and thoughtful design</em>.
            </p>
        </section>

        <section class="about-founder">
            <h2>Our Founder</h2>
            <div class="founder-card">
                <div class="founder-details">
                    <p>
                        <strong>Arnab Mazumder</strong>, a passionate entrepreneur with a background in design and engineering, founded Furniture Store to bridge the gap between style, comfort, and affordability. Arnab’s conviction: “Every home deserves furniture that is both functional and inspiring—crafted for life, and designed for you.”<br>
                        <br>
                        <a href="mailto:arnab.mazumder1108@gmail.com" class="about-link">arnab.mazumder1108@gmail.com</a>
                        |
                        <a href="https://www.linkedin.com/in/arnab-mazumder-b4a238326/" class="about-link" target="_blank">LinkedIn Profile</a>
                    </p>
                </div>
            </div>
        </section>
    </div>
    <section class="about-values">
        <h2>What We Stand For</h2>
        <ul>
            <li>
                <strong>Timeless Design:</strong> Whether your taste runs to minimal Scandinavian, rich Indian tradition, or sleek urban chic, our collections offer something to elevate every space.
            </li>
            <li>
                <strong>Superior Craftsmanship:</strong> Our furniture is made using premium, responsibly-sourced wood, resilient metals, eco-friendly fabrics, and carefully detailed finishes. Each piece is the culmination of hours of artistry and expertise.
            </li>
            <li>
                <strong>Affordability with Integrity:</strong> By controlling production and logistics, we deliver high-end quality at prices that make sense—ensuring great design and durability are accessible to every household.
            </li>
            <li>
                <strong>Sustainability Matters:</strong> From responsibly-sourced materials to eco-friendly packaging, our commitment to the planet is reflected in every process and product.
            </li>
        </ul>
    </section>
    <section class="about-promise">
        <h2>Why Choose Us?</h2>
        <ul>
            <li><strong>Wide Variety:</strong> We bring you everything from cozy beds and elegant dining sets to practical storage, modern office solutions, and custom modular wardrobes.</li>
            <li><strong>Personalized Service:</strong> Need layout guidance? Our interior design team is a message away, and our bespoke services let you customize furniture to your precise needs.</li>
            <li><strong>Reliable Delivery:</strong> We deliver safely and promptly all across India. In select locations, our team will assemble your furniture so it’s ready to use, worry-free.</li>
            <li><strong>Trusted by Thousands:</strong> Our reputation is built on word-of-mouth, five-star reviews, and loyal clients. From newlyweds to leading businesses, our customers trust us at every stage of life.</li>
        </ul>
        <p class="about-note">
            We’re more than a store—we’re a partner in your journey. Whether you’re furnishing your first apartment, upgrading your family home, or setting up an inspiring workspace, we’re here to help make your everyday beautiful.
        </p>
    </section>
</div>

<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&family=Merriweather:wght@400;700&display=swap');
:root {
    --primary: #233554;
    --primary-bg: #f8fafc;
    --surface: #fff;
    --border: #e0e8f0;
    --navy: #20283b;
    --gray: #768199;
    --danger: #cc4444;
    --accent: #4b91e2;
    --shadow: 0 2px 16px 0 rgba(36,46,81,0.09);
}
body {
    font-family: 'Inter', Arial, Helvetica, sans-serif;
    color: var(--navy);
    background: var(--primary-bg);
}
.about-us-container {
    max-width: 820px;
    margin: 28px auto 42px auto;
    background: var(--surface);
    border-radius: 15px;
    box-shadow: var(--shadow);
    padding: 2.7em 2.4em;
    font-family: 'Inter', Arial, Helvetica, sans-serif;
    line-height: 1.75;
    border: 1.2px solid var(--border);
}
.about-us-container h1 {
    font-family: 'Merriweather', serif;
    font-size: 2.5rem;
    font-weight: bold;
    letter-spacing: 0.01em;
    color: var(--primary);
    text-align: left;
    margin-bottom: 0.8em;
}
.about-us-container h2 {
    font-family: 'Merriweather', serif;
    font-size: 1.31em;
    color: var(--primary);
    margin-top: 1.5em;
    margin-bottom: 0.65em;
    font-weight: 700;
    letter-spacing: 0.013em;
}
.about-split {
    display: flex;
    flex-direction: row;
    gap: 2.6em;
    margin-bottom: 0.4em;
}
.about-intro, .about-founder {
    flex: 1 1 0;
}
@media (max-width: 900px) {
    .about-split { flex-direction: column; gap: 1.5em;}
}
.founder-card {
    display: flex;
    align-items: flex-start;
    gap: 1.1em;
    margin-bottom: 1.3em;
    background: #f4f7fc;
    border-radius: 10px;
    padding: 1.1em 1.2em 1.1em 1.1em;
    box-shadow: 0 1px 12px #2335540b;
    border: 1px solid #eaf2fa;
}
.founder-photo {
    width: 75px;
    height: 75px;
    border-radius: 54%;
    object-fit: cover;
    border: 2.3px solid #b7cbe2;
    box-shadow: 0 2px 9px #22315327;
}
.founder-details {
    flex: 1 1 0;
    font-size: 1.07em;
    color: var(--gray);
    font-style: normal;
}
.about-link {
    color: var(--accent);
    font-weight: 600;
    text-decoration: none;
    font-size: 1em;
}
.about-link:hover {
    text-decoration: underline;
    color: var(--danger);
}
.about-values, .about-promise {
    margin-top: 1.1em;
    margin-bottom: 1.7em;
}
.about-values ul, .about-promise ul {
    margin-left: 1.2em;
    margin-bottom: 0.6em;
}
.about-values li, .about-promise li {
    margin-bottom: 0.72em;
    font-size: 1.05em;
    color: #323e5a;
    padding-left: 0.1em;
}
.about-note {
    margin-top: 1.65em;
    background: #f9fbee;
    color: #36544a;
    border-left: 4px solid #c7eace;
    border-radius: 7px;
    font-size: 1.06em;
    padding: 1.04em 1.3em 1.1em 1.08em;
    box-shadow: 0 1px 7px #dff6ec17;
}
@media (max-width:650px) {
    .about-us-container { padding: 0.9em 0.6em;}
    .about-split { flex-direction: column; gap: 0.7em;}
    .founder-photo { width: 55px; height: 55px;}
    .about-link { font-size: 0.98em;}
    .about-values li, .about-promise li {font-size: .99em;}
}
</style>
<?php include 'includes/footer.php'; ?>
