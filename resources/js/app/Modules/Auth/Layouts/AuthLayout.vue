<template>
    <div class="auth-layout">
        <section class="auth-visual">
            <div class="visual-background"></div>

            <div class="visual-content">
                <div class="brand">
                    <v-icon
                        icon="mdi-horse-variant"
                        size="38"
                    />

                    <span>StableManager</span>
                </div>

                <div class="visual-message">
                    <div class="eyebrow">
                        YOUR STABLE. YOUR COMMUNITY.
                    </div>

                    <h1>
                        Gemeinsam mehr bewegen.
                    </h1>

                    <p>
                        Alles rund um Pferde, Menschen und deinen Stall
                        an einem Ort. Organisiert, verbunden und immer
                        im Blick.
                    </p>

                    <div class="visual-benefits">
                        <div class="benefit">
                            <v-icon icon="mdi-horse" />
                            <span>Mit Leidenschaft für Pferde</span>
                        </div>

                        <div class="benefit">
                            <v-icon icon="mdi-account-group-outline" />
                            <span>Für die ganze Stallgemeinschaft</span>
                        </div>

                        <div class="benefit">
                            <v-icon icon="mdi-leaf" />
                            <span>Gemeinsam den Alltag gestalten</span>
                        </div>
                    </div>
                </div>

                <div class="visual-footer">
                    <span>StableManager</span>
                    <span>Made for the equestrian community</span>
                </div>
            </div>

            <div class="decorative-circle circle-one"></div>
            <div class="decorative-circle circle-two"></div>
        </section>

        <main class="auth-content">
            <div class="mobile-brand">
                <v-icon icon="mdi-horse-variant" size="32" />
                <span>StableManager</span>
            </div>

            <div class="auth-content-inner">
                <slot />
            </div>

            <footer class="content-footer">
                <span>© {{ new Date().getFullYear() }} StableManager</span>
                <span>Dein Stall. Deine Gemeinschaft.</span>
            </footer>
        </main>
    </div>
</template>

<style scoped>
.auth-layout {
    display: grid;
    grid-template-columns: minmax(0, 1fr) minmax(440px, 0.85fr);
    min-height: 100vh;
    background: #f8f6f1;
    color: #303b33;
}

.auth-visual {
    position: relative;
    display: flex;
    min-height: 100vh;
    overflow: hidden;
    background: #35483d;
    color: #f5f1e9;
}

.visual-background {
    position: absolute;
    inset: 0;
    background:
        radial-gradient(
            circle at 80% 20%,
            rgba(177, 139, 94, 0.25),
            transparent 35%
        ),
        linear-gradient(
            145deg,
            #465b4c 0%,
            #35483d 48%,
            #27382e 100%
        );
}

.visual-content {
    position: relative;
    z-index: 2;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    width: 100%;
    max-width: 780px;
    min-height: 100vh;
    padding: 56px clamp(36px, 7vw, 100px);
}

.brand {
    display: flex;
    align-items: center;
    gap: 12px;
    font-size: 25px;
    font-weight: 700;
    letter-spacing: -0.8px;
}

.visual-message {
    max-width: 570px;
    margin: 70px 0;
}

.eyebrow {
    margin-bottom: 24px;
    color: #d6c7ae;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 3px;
}

.visual-message h1 {
    max-width: 500px;
    margin-bottom: 24px;
    font-size: clamp(42px, 5vw, 68px);
    font-weight: 600;
    line-height: 1.08;
    letter-spacing: -2px;
}

.visual-message > p {
    max-width: 440px;
    color: #e1d8c9;
    font-size: 17px;
    line-height: 1.8;
}

.visual-benefits {
    display: flex;
    flex-direction: column;
    gap: 18px;
    margin-top: 46px;
}

.benefit {
    display: flex;
    align-items: center;
    gap: 14px;
    color: #e1d8c9;
    font-size: 14px;
}

.benefit :deep(.v-icon) {
    color: #d6b98c;
}

.visual-footer {
    display: flex;
    justify-content: space-between;
    gap: 16px;
    color: #c4c8bd;
    font-size: 11px;
}

.decorative-circle {
    position: absolute;
    z-index: 1;
    border: 1px solid rgba(245, 241, 233, 0.08);
    border-radius: 50%;
    pointer-events: none;
}

.circle-one {
    width: 480px;
    height: 480px;
    right: -220px;
    top: 10%;
}

.circle-two {
    width: 680px;
    height: 680px;
    right: -320px;
    top: 2%;
}

.auth-content {
    display: flex;
    flex-direction: column;
    justify-content: center;
    min-height: 100vh;
    padding: 48px clamp(28px, 6vw, 100px);
}

.auth-content-inner {
    width: 100%;
    max-width: 440px;
    margin: auto;
}

.mobile-brand {
    display: none;
    align-items: center;
    gap: 10px;
    margin-bottom: 36px;
    color: #35483d;
    font-size: 22px;
    font-weight: 700;
}

.content-footer {
    display: flex;
    justify-content: space-between;
    gap: 12px;
    color: #938d82;
    font-size: 11px;
}

@media (max-width: 1000px) {
    .auth-layout {
        grid-template-columns: minmax(0, 0.9fr) minmax(400px, 1fr);
    }

    .visual-content {
        padding: 40px;
    }

    .auth-content {
        padding: 40px 32px;
    }

    .visual-message h1 {
        font-size: 48px;
    }
}

@media (max-width: 760px) {
    .auth-layout {
        display: block;
    }

    .auth-visual {
        display: none;
    }

    .auth-content {
        min-height: 100vh;
        padding: 32px 24px;
    }

    .mobile-brand {
        display: flex;
    }

    .content-footer {
        margin-top: 40px;
    }
}

@media (max-width: 420px) {
    .content-footer {
        flex-direction: column;
    }
}
</style>
