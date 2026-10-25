import React from 'react';
import { Composition } from 'remotion';
import { PrintShebaPromo, SCENE_DURATIONS, TOTAL_PROMO_FRAMES } from './PrintShebaPromo';
import { Scene1Hook } from './scenes/Scene1Hook';
import { Scene2Problems } from './scenes/Scene2Problems';
import { Scene3Reveal } from './scenes/Scene3Reveal';
import { Scene4HowItWorks } from './scenes/Scene4HowItWorks';
import { Scene5Features } from './scenes/Scene5Features';
import { Scene6Transformation } from './scenes/Scene6Transformation';
import { Scene7CTA } from './scenes/Scene7CTA';
import { TutorialCustomer } from './tutorials/TutorialCustomer';
import { TutorialShopkeeper } from './tutorials/TutorialShopkeeper';
import { TutorialShopkeeperFull } from './tutorials/TutorialShopkeeperFull';
import { TutorialOffice } from './tutorials/TutorialOffice';

export const RemotionRoot: React.FC = () => {
  return (
    <>
      {/* 1. Master Promotional Video (149.33s = 4480 frames, 1920x1080 @ 30 FPS, Voiceover Enabled) */}
      <Composition
        id="PrintShebaPromo"
        component={PrintShebaPromo}
        durationInFrames={TOTAL_PROMO_FRAMES}
        fps={30}
        width={1920}
        height={1080}
        defaultProps={{
          enableVoiceover: true,
        }}
      />

      {/* 2. Individual Scene Compositions for Fast Previewing & Fine-Tuning */}
      <Composition
        id="Scene1-Hook"
        component={Scene1Hook}
        durationInFrames={SCENE_DURATIONS.scene1}
        fps={30}
        width={1920}
        height={1080}
      />

      <Composition
        id="Scene2-Problems"
        component={Scene2Problems}
        durationInFrames={SCENE_DURATIONS.scene2}
        fps={30}
        width={1920}
        height={1080}
      />

      <Composition
        id="Scene3-Reveal"
        component={Scene3Reveal}
        durationInFrames={SCENE_DURATIONS.scene3}
        fps={30}
        width={1920}
        height={1080}
      />

      <Composition
        id="Scene4-HowItWorks"
        component={Scene4HowItWorks}
        durationInFrames={SCENE_DURATIONS.scene4}
        fps={30}
        width={1920}
        height={1080}
      />

      <Composition
        id="Scene5-Features"
        component={Scene5Features}
        durationInFrames={SCENE_DURATIONS.scene5}
        fps={30}
        width={1920}
        height={1080}
      />

      <Composition
        id="Scene6-Transformation"
        component={Scene6Transformation}
        durationInFrames={SCENE_DURATIONS.scene6}
        fps={30}
        width={1920}
        height={1080}
      />

      <Composition
        id="Scene7-CTA"
        component={Scene7CTA}
        durationInFrames={SCENE_DURATIONS.scene7}
        fps={30}
        width={1920}
        height={1080}
      />

      {/* 3. Dedicated User & Shop Walkthrough Tutorials with Real UI Mockups */}
      {/* Tutorial 1: Customer Self-Service Guide (60s = 1800 frames @ 30 FPS) */}
      <Composition
        id="Tutorial-Customer"
        component={TutorialCustomer}
        durationInFrames={1800}
        fps={30}
        width={1920}
        height={1080}
      />

      {/* Tutorial 2: Shopkeeper 5-Minute Setup & Dashboard Guide (90s = 2700 frames @ 30 FPS) */}
      <Composition
        id="Tutorial-Shopkeeper"
        component={TutorialShopkeeper}
        durationInFrames={2700}
        fps={30}
        width={1920}
        height={1080}
      />

      {/* Tutorial 2 (Full Extended): Complete Shop Onboarding, Windows Agent & Android APK Guide (133.33s = 4000 frames @ 30 FPS) */}
      <Composition
        id="Tutorial-Shopkeeper-Full"
        component={TutorialShopkeeperFull}
        durationInFrames={4000}
        fps={30}
        width={1920}
        height={1080}
      />

      {/* Tutorial 3: Office Free Print & Smart AI NID Card Guide (60s = 1800 frames @ 30 FPS) */}
      <Composition
        id="Tutorial-Office"
        component={TutorialOffice}
        durationInFrames={1800}
        fps={30}
        width={1920}
        height={1080}
      />
    </>
  );
};
