-- Sample categories and posts. Run after schema.sql.
USE blog_cms;

INSERT INTO categories (name, slug) VALUES
    ('PHP', 'php'),
    ('Databases', 'databases'),
    ('Career', 'career');

INSERT INTO posts (category_id, title, slug, excerpt, content, status, seo_title, seo_description, published_at) VALUES
(1, 'Why I Started Learning PHP', 'why-i-started-learning-php',
 'Notes on choosing PHP as a first backend language.',
 'PHP powers a large share of the web, which makes it a practical first backend language.

It runs on cheap hosting, needs no build step, and lets you see results on a page within minutes.',
 'published', 'Why I Started Learning PHP as a Beginner',
 'Why PHP is a practical first backend language, and what I learned building my first site.',
 '2026-09-01 10:00:00'),

(1, 'Using PDO Prepared Statements', 'using-pdo-prepared-statements',
 'How prepared statements keep user input out of your SQL.',
 'Never put form input straight into an SQL string. Use placeholders instead, and pass the values separately.

PDO sends the query and the data apart, so the database never mistakes input for a command.',
 'published', NULL, NULL,
 '2026-09-14 14:20:00'),

(2, 'Designing a Simple Posts Table', 'designing-a-simple-posts-table',
 'Columns, status values, and an index for the home page.',
 'A posts table needs a unique slug, a status column, and timestamps.

An index on status and publish date keeps the home page fast as the number of posts grows.',
 'published', 'Designing a Simple Posts Table in MySQL', NULL,
 '2026-09-22 09:30:00'),

(3, 'Writing a Portfolio Project Write-up', 'writing-a-portfolio-project-write-up',
 'What to include so a reader understands your project in two minutes.',
 'Start with the problem, then explain your choices, then show the result.

A short write-up with a clear screenshot beats a long one nobody reads.',
 'draft', NULL, NULL,
 NULL),

(3, 'Preparing for Your First Developer Interview', 'preparing-for-your-first-developer-interview',
 'A simple plan for the two weeks before an interview.',
 'Practise explaining one of your projects out loud, from problem to result.

Review the basics of the language you will be asked about, and prepare two questions to ask the interviewer.',
 'published', NULL, 'A simple two-week plan for preparing for your first developer interview.',
 '2026-10-02 11:45:00');
