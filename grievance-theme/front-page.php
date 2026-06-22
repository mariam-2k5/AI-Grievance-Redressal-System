<?php get_header(); ?>

<style>
/* ===== HERO SECTION ===== */
.hero {
    background: linear-gradient(135deg, #0f766e, #34d399);
    color: white;
    padding: 100px 20px;
    text-align: center;
}

.hero h1 {
    font-size: 46px;
    margin-bottom: 10px;
}

.hero p {
    font-size: 18px;
    max-width: 700px;
    margin: auto;
}

.hero-buttons {
    margin-top: 30px;
}

.hero-buttons a {
    text-decoration: none;
    padding: 14px 28px;
    margin: 8px;
    border-radius: 25px;
    font-weight: bold;
    display: inline-block;
}

.btn-primary {
    background: white;
    color: #0f766e;
}

.btn-secondary {
    border: 2px solid white;
    color: white;
}

/* ===== SECTIONS ===== */
.section {
    padding: 70px 20px;
    text-align: center;
}

.steps {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 25px;
    max-width: 1000px;
    margin: auto;
}

.step {
    background: #f9fafb;
    padding: 30px;
    border-radius: 15px;
    transition: 0.3s;
}

.step:hover {
    transform: translateY(-8px);
}

/* ===== STATS ===== */
.stats {
    background: #ecfeff;
    padding: 60px 20px;
}

.stat-box {
    display: flex;
    justify-content: center;
    gap: 50px;
    flex-wrap: wrap;
}

.stat {
    font-size: 34px;
    font-weight: bold;
    color: #0f766e;
}

.stat span {
    display: block;
    font-size: 15px;
    color: #444;
}
</style>

<!-- HERO -->
<section class="hero">
    <h1>AI Grievance Redressal System</h1>
    <p>A transparent, AI-powered platform for students to submit and track college grievances efficiently.</p>

    <div class="hero-buttons">
	<a href="<?php echo site_url('/submit-grievance'); ?>" class="btn-primary">
    Submit Grievance
</a>

<a href="<?php echo site_url('/track-grievance'); ?>" class="btn-secondary">
    Track Grievance
</a>


    </div>
</section>

<!-- HOW IT WORKS -->
<section class="section">
    <h2>How It Works</h2>
    <div class="steps">
        <div class="step">
            <h3>📩 Submit</h3>
            <p>Students submit grievances through an online form.</p>
        </div>
        <div class="step">
            <h3>🤖 AI Analysis</h3>
            <p>AI automatically categorizes and prioritizes grievances.</p>
        </div>
        <div class="step">
            <h3>✅ Resolution</h3>
            <p>Authorities review, respond, and update status.</p>
        </div>
    </div>
</section>

<!-- STATS -->
<section class="privacy-section">
    <h2>Privacy & Trust Commitment</h2>
    <p class="privacy-subtitle">
        Your grievance is handled with confidentiality, transparency, and accountability.
    </p>

    <div class="privacy-grid">
        <div class="privacy-card">
            🔒
            <h4>Confidential Submissions</h4>
            <p>
                Student grievances are securely stored and visible only to authorized administrators.
            </p>
        </div>

        <div class="privacy-card">
            👁️
            <h4>Transparent Tracking</h4>
            <p>
                Every grievance receives a unique tracking ID so students can monitor status updates anytime.
            </p>
        </div>

        <div class="privacy-card">
            🛡️
            <h4>Role-Based Access</h4>
            <p>
                Only admins can review, respond, and resolve grievances ensuring data privacy and integrity.
            </p>
        </div>
    </div>
</section>

<?php get_footer(); ?>
