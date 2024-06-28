import React from 'react';
import { createRoot } from 'react-dom/client';
import Chat from './js/Chat';
import './styles/app.css'

const container = document.getElementById('root');
const root = createRoot(container);
root.render(<Chat />);

import Chart from "chart.js/auto";

console.log("This log comes from assets/app.js - welcome to AssetMapper! 🎉");
