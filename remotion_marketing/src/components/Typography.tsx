import React from 'react';
import { interpolate, spring, useCurrentFrame, useVideoConfig } from 'remotion';

interface BadgeProps {
  text: string;
  icon?: string;
  color?: 'rose' | 'amber' | 'emerald' | 'blue' | 'purple';
  delay?: number;
}

export const Badge: React.FC<BadgeProps> = ({
  text,
  icon,
  color = 'rose',
  delay = 0,
}) => {
  const frame = useCurrentFrame();
  const { fps } = useVideoConfig();

  const anim = spring({
    frame: frame - delay,
    fps,
    config: { damping: 14, stiffness: 120 },
  });

  const colorStyles = {
    rose: {
      bg: 'rgba(244, 63, 94, 0.15)',
      border: 'rgba(244, 63, 94, 0.4)',
      text: '#fda4af',
      dot: '#f43f5e',
    },
    amber: {
      bg: 'rgba(245, 158, 11, 0.15)',
      border: 'rgba(245, 158, 11, 0.4)',
      text: '#fcd34d',
      dot: '#f59e0b',
    },
    emerald: {
      bg: 'rgba(16, 185, 129, 0.15)',
      border: 'rgba(16, 185, 129, 0.4)',
      text: '#6ee7b7',
      dot: '#10b981',
    },
    blue: {
      bg: 'rgba(59, 130, 246, 0.15)',
      border: 'rgba(59, 130, 246, 0.4)',
      text: '#93c5fd',
      dot: '#3b82f6',
    },
    purple: {
      bg: 'rgba(168, 85, 247, 0.15)',
      border: 'rgba(168, 85, 247, 0.4)',
      text: '#d8b4fe',
      dot: '#a855f7',
    },
  }[color];

  return (
    <div
      style={{
        display: 'inline-flex',
        alignItems: 'center',
        gap: 10,
        padding: '8px 20px',
        borderRadius: 9999,
        backgroundColor: colorStyles.bg,
        border: `1.5px solid ${colorStyles.border}`,
        color: colorStyles.text,
        fontSize: 22,
        fontWeight: 700,
        fontFamily: "'Hind Siliguri', sans-serif",
        transform: `scale(${anim})`,
        opacity: interpolate(anim, [0, 1], [0, 1]),
        backdropFilter: 'blur(8px)',
        boxShadow: `0 8px 24px -4px ${colorStyles.bg}`,
      }}
    >
      <span
        style={{
          width: 10,
          height: 10,
          borderRadius: '50%',
          backgroundColor: colorStyles.dot,
          boxShadow: `0 0 10px ${colorStyles.dot}`,
        }}
      />
      {icon && <span>{icon}</span>}
      <span>{text}</span>
    </div>
  );
};

interface HeadingProps {
  children: React.ReactNode;
  gradient?: 'rose' | 'emerald' | 'gold' | 'cyan' | 'none';
  size?: number;
  delay?: number;
  style?: React.CSSProperties;
}

export const Title: React.FC<HeadingProps> = ({
  children,
  gradient = 'none',
  size = 56,
  delay = 0,
  style,
}) => {
  const frame = useCurrentFrame();
  const { fps } = useVideoConfig();

  const scale = spring({
    frame: frame - delay,
    fps,
    config: { damping: 16, stiffness: 100 },
  });

  const translateY = interpolate(scale, [0, 1], [40, 0]);
  const opacity = interpolate(scale, [0, 1], [0, 1]);

  let backgroundStyle: React.CSSProperties = { color: '#ffffff' };
  if (gradient === 'rose') {
    backgroundStyle = {
      backgroundImage: 'linear-gradient(135deg, #ffffff 30%, #fb7185 70%, #f43f5e 100%)',
      WebkitBackgroundClip: 'text',
      WebkitTextFillColor: 'transparent',
    };
  } else if (gradient === 'emerald') {
    backgroundStyle = {
      backgroundImage: 'linear-gradient(135deg, #ffffff 30%, #34d399 70%, #059669 100%)',
      WebkitBackgroundClip: 'text',
      WebkitTextFillColor: 'transparent',
    };
  } else if (gradient === 'gold') {
    backgroundStyle = {
      backgroundImage: 'linear-gradient(135deg, #ffffff 20%, #fcd34d 60%, #f59e0b 100%)',
      WebkitBackgroundClip: 'text',
      WebkitTextFillColor: 'transparent',
    };
  } else if (gradient === 'cyan') {
    backgroundStyle = {
      backgroundImage: 'linear-gradient(135deg, #ffffff 30%, #38bdf8 70%, #0284c7 100%)',
      WebkitBackgroundClip: 'text',
      WebkitTextFillColor: 'transparent',
    };
  }

  return (
    <h1
      style={{
        margin: 0,
        fontSize: size,
        fontWeight: 900,
        fontFamily: "'Hind Siliguri', sans-serif",
        lineHeight: 1.25,
        letterSpacing: '-0.02em',
        transform: `translateY(${translateY}px) scale(${scale})`,
        opacity,
        textAlign: 'center',
        ...backgroundStyle,
        ...style,
      }}
    >
      {children}
    </h1>
  );
};
