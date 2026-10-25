import React from 'react';
import { Audio, interpolate, Sequence, staticFile, useCurrentFrame } from 'remotion';
import { Scene1Hook } from './scenes/Scene1Hook';
import { Scene2Problems } from './scenes/Scene2Problems';
import { Scene3Reveal } from './scenes/Scene3Reveal';
import { Scene4HowItWorks } from './scenes/Scene4HowItWorks';
import { Scene5Features } from './scenes/Scene5Features';
import { Scene6Transformation } from './scenes/Scene6Transformation';
import { Scene7CTA } from './scenes/Scene7CTA';

// Scene Duration Constants tightly synchronized to voiceover.mp3 (149.32s = 4480 frames at 30 FPS)
export const SCENE_DURATIONS = {
  scene1: 470, // 0.0s - 15.67s (Hook)
  scene2: 665, // 15.67s - 37.83s (Problems & Privacy)
  scene3: 545, // 37.83s - 56.00s (PrintSheba Reveal)
  scene4: 735, // 56.00s - 80.50s (How It Works: 3 Steps)
  scene5: 915, // 80.50s - 111.00s (5 Powerful Features)
  scene6: 540, // 111.00s - 129.00s (Business Transformation & Profit)
  scene7: 610, // 129.00s - 149.33s (High-Impact CTA & Closing)
};

export const TOTAL_PROMO_FRAMES =
  SCENE_DURATIONS.scene1 +
  SCENE_DURATIONS.scene2 +
  SCENE_DURATIONS.scene3 +
  SCENE_DURATIONS.scene4 +
  SCENE_DURATIONS.scene5 +
  SCENE_DURATIONS.scene6 +
  SCENE_DURATIONS.scene7; // Exactly 4480 frames = 149.33s

// Scene wrapper with smooth 12-frame crossfade
const SceneContainer: React.FC<{
  children: React.ReactNode;
  durationInFrames: number;
}> = ({ children, durationInFrames }) => {
  const frame = useCurrentFrame();

  const opacity = interpolate(
    frame,
    [0, 12, durationInFrames - 12, durationInFrames],
    [0, 1, 1, 0],
    { extrapolateLeft: 'clamp', extrapolateRight: 'clamp' }
  );

  return (
    <div
      style={{
        position: 'absolute',
        inset: 0,
        opacity,
        fontFamily: "'Hind Siliguri', sans-serif",
      }}
    >
      {children}
    </div>
  );
};

export interface PromoProps {
  enableVoiceover?: boolean;
}

export const PrintShebaPromo: React.FC<PromoProps> = ({ enableVoiceover = true }) => {
  let currentFrom = 0;

  const s1From = currentFrom;
  currentFrom += SCENE_DURATIONS.scene1;

  const s2From = currentFrom;
  currentFrom += SCENE_DURATIONS.scene2;

  const s3From = currentFrom;
  currentFrom += SCENE_DURATIONS.scene3;

  const s4From = currentFrom;
  currentFrom += SCENE_DURATIONS.scene4;

  const s5From = currentFrom;
  currentFrom += SCENE_DURATIONS.scene5;

  const s6From = currentFrom;
  currentFrom += SCENE_DURATIONS.scene6;

  const s7From = currentFrom;

  return (
    <div
      style={{
        position: 'relative',
        width: 1920,
        height: 1080,
        backgroundColor: '#070b14',
        overflow: 'hidden',
      }}
    >
      {/* ElevenLabs High-Quality Voiceover Audio Track */}
      {enableVoiceover && (
        <Audio src={staticFile('audio/voiceover.mp3')} volume={1} />
      )}

      {/* Global Google Fonts for Bengali & Modern Typography */}
      <style>{`
        @import url('https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@600;700;800;900&display=swap');
      `}</style>

      {/* SCENE 1: THE HOOK (0 - 470) */}
      <Sequence from={s1From} durationInFrames={SCENE_DURATIONS.scene1} name="Scene 1: Hook">
        <SceneContainer durationInFrames={SCENE_DURATIONS.scene1}>
          <Scene1Hook />
        </SceneContainer>
      </Sequence>

      {/* SCENE 2: THE PROBLEMS (470 - 1135) */}
      <Sequence from={s2From} durationInFrames={SCENE_DURATIONS.scene2} name="Scene 2: Problems">
        <SceneContainer durationInFrames={SCENE_DURATIONS.scene2}>
          <Scene2Problems />
        </SceneContainer>
      </Sequence>

      {/* SCENE 3: THE REVEAL (1135 - 1680) */}
      <Sequence from={s3From} durationInFrames={SCENE_DURATIONS.scene3} name="Scene 3: Reveal">
        <SceneContainer durationInFrames={SCENE_DURATIONS.scene3}>
          <Scene3Reveal />
        </SceneContainer>
      </Sequence>

      {/* SCENE 4: HOW IT WORKS (1680 - 2415) */}
      <Sequence from={s4From} durationInFrames={SCENE_DURATIONS.scene4} name="Scene 4: How It Works">
        <SceneContainer durationInFrames={SCENE_DURATIONS.scene4}>
          <Scene4HowItWorks />
        </SceneContainer>
      </Sequence>

      {/* SCENE 5: POWERFUL FEATURES (2415 - 3330) */}
      <Sequence from={s5From} durationInFrames={SCENE_DURATIONS.scene5} name="Scene 5: Features">
        <SceneContainer durationInFrames={SCENE_DURATIONS.scene5}>
          <Scene5Features />
        </SceneContainer>
      </Sequence>

      {/* SCENE 6: TRANSFORMATION (3330 - 3870) */}
      <Sequence from={s6From} durationInFrames={SCENE_DURATIONS.scene6} name="Scene 6: Transformation">
        <SceneContainer durationInFrames={SCENE_DURATIONS.scene6}>
          <Scene6Transformation />
        </SceneContainer>
      </Sequence>

      {/* SCENE 7: CALL TO ACTION (3870 - 4480) */}
      <Sequence from={s7From} durationInFrames={SCENE_DURATIONS.scene7} name="Scene 7: Call to Action">
        <SceneContainer durationInFrames={SCENE_DURATIONS.scene7}>
          <Scene7CTA />
        </SceneContainer>
      </Sequence>
    </div>
  );
};
