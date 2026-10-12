import js from '@eslint/js';
import prettier from 'eslint-config-prettier/flat';
import react from 'eslint-plugin-react';
import reactHooks from 'eslint-plugin-react-hooks';
import globals from 'globals';
import typescript from 'typescript-eslint';

export default [
  js.configs.recommended,
  reactHooks.configs.flat.recommended,
  ...typescript.configs.recommended,
  {
    files: ['resources/js/**/*.{js,jsx,ts,tsx}'],
    ...react.configs.flat.recommended,
    ...react.configs.flat['jsx-runtime'],
    languageOptions: {
      globals: {
        ...globals.browser,
        ...globals.node,
      },
    },
    rules: {
      'react/react-in-jsx-scope': 'off',
      'react/prop-types': 'off',
      'react/no-unescaped-entities': 'off',
    },
    settings: {
      react: {
        version: 'detect',
      },
    },
  },
  {
    ignores: [
      '**/node_modules/**',
      '**/vendor/**',
      '**/*.min.js',
      '**/*.bundle.js',
      'public/**',
      'bootstrap/ssr/**',
      'tailwind.config.js',
    ],
  },
  prettier,
];
