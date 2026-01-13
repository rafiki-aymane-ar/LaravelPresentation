import Sidebar from './Sidebar';
import MainContent from './MainContent';
import ThemeToggle from './ThemeToggle';
import './index.css';
import 'highlight.js/styles/atom-one-dark.css';

function App() {
  return (
    <div className="app-layout">
      <ThemeToggle />
      <Sidebar />
      <MainContent />
    </div>
  );
}

export default App;
