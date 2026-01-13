import { useEffect, useRef } from 'react';
import hljs from 'highlight.js';
import contentData from './data/index.js';

export default function MainContent() {
    const mainRef = useRef(null);

    useEffect(() => {
        // Highlight code blocks after rendering
        if (mainRef.current) {
            mainRef.current.querySelectorAll('pre code').forEach((block) => {
                if (block.dataset.highlighted) {
                    return;
                }
                hljs.highlightElement(block);
            });
        }
    }, []);

    const renderBlock = (block, index) => {
        switch (block.type) {
            case 'paragraph':
                return <p key={index} dangerouslySetInnerHTML={{ __html: block.text }} />;
            case 'list':
                const ListTag = block.ordered ? 'ol' : 'ul';
                return (
                    <ListTag key={index}>
                        {block.items.map((item, idx) => (
                            <li key={idx} dangerouslySetInnerHTML={{ __html: item }} />
                        ))}
                    </ListTag>
                );
            case 'subheader':
                return <h3 key={index} dangerouslySetInnerHTML={{ __html: block.text }} />;
            case 'subsubheader':
                return <h4 key={index} dangerouslySetInnerHTML={{ __html: block.text }} />;
            case 'quote':
                return (
                    <blockquote key={index} dangerouslySetInnerHTML={{ __html: block.text }} />
                );
            case 'code':
                return (
                    <pre key={index}>
                        <code className={`language-${block.language}`}>
                            {block.code}
                        </code>
                    </pre>
                );
            case 'table':
                return (
                    <table key={index} className={block.className || ''}>
                        <thead>
                            <tr>
                                {block.headers.map((header, idx) => (
                                    <th key={idx}>{header}</th>
                                ))}
                            </tr>
                        </thead>
                        <tbody>
                            {block.rows.map((row, rIdx) => (
                                <tr key={rIdx}>
                                    {row.map((cell, cIdx) => (
                                        <td key={cIdx}>
                                            {typeof cell === 'object' && cell.isBadge ? (
                                                <span className="method-badge">{cell.content}</span>
                                            ) : (
                                                cell
                                            )}
                                        </td>
                                    ))}
                                </tr>
                            ))}
                        </tbody>
                    </table>
                );
            case 'separator':
                return <hr key={index} />;
            default:
                return null;
        }
    };

    return (
        <main className="main-content" ref={mainRef}>
            <h1>Présentation : Routage et Middleware dans Laravel 12</h1>

            <div className="content">
                {contentData.map((section) => (
                    <section key={section.id}>
                        {/* Render Section Title as H2 (unless it's Introduction which was treated uniquely) */}
                        <h2 id={section.id}>
                            {section.id === 'intro' ? (
                                <strong>{section.title}</strong>
                            ) : (
                                section.title
                            )}
                        </h2>

                        {/* Render Blocks */}
                        {section.content.map((block, index) => renderBlock(block, index))}
                    </section>
                ))}
            </div>
        </main>
    );
}
