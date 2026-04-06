-- VulnSphere Seed Data
-- Passwords are MD5 hashed (weak, no salt)
-- admin    -> password: admin123      -> md5: 0192023a7bbd73250516f069df18b500
-- alice    -> password: alice2024     -> md5: d0763edaa9d9bd2a9516280e9044d885
-- bob      -> password: bob_rocks99  -> md5: 3d6b7d8b72b2b6b37b2b6b37b2b6b37b (placeholder)
-- dave     -> password: letmein      -> md5: 0d107d09f5bbe40cade3de5c71e9e9b7

INSERT OR IGNORE INTO users (id, username, email, password, bio, avatar, role) VALUES
(1, 'admin', 'admin@vulnsphere.io', '0192023a7bbd73250516f069df18b500', 'Platform administrator. Building the future of social.', 'avatar_admin.png', 'admin'),
(2, 'alice_dev', 'alice@vulnsphere.io', 'd0763edaa9d9bd2a9516280e9044d885', 'Full-stack dev @VulnSphere. Coffee & code. she/her 💻', 'avatar_alice.png', 'user'),
(3, 'b0bbydrops', 'bob@example.com', '21232f297a57a5a743894a0e4a801fc3', 'Security researcher. CTF player. Bug bounty hunter 🔍', 'avatar_bob.png', 'user'),
(4, 'dave_xyz', 'dave@gmail.com', '0d107d09f5bbe40cade3de5c71e9e9b7', 'Just vibing. Designer & photographer 📸', 'default.png', 'user');

INSERT OR IGNORE INTO posts (id, user_id, content, image, likes) VALUES
(1, 1, 'Welcome to VulnSphere! 🚀 The social platform for builders and creators. Share your work, connect with others, and grow together.', NULL, 24),
(2, 2, 'Just shipped a new feature to production at 2am... and it works??? This never happens lol. Sleep is for the weak apparently. #devlife #shipping', NULL, 57),
(3, 3, 'Hot take: most "secure" auth systems are held together with duct tape and hope. Change my mind. #security #infosec', NULL, 89),
(4, 2, 'My setup finally came together. Three monitors, mechanical keyboard, and way too much caffeine. DM me if you want my desk tour 🖥️', 'post_desk.jpg', 31),
(5, 4, 'Golden hour hits different when you actually go outside sometimes. Reminder to skill-touch some grass occasionally 🌅', 'post_sunset.jpg', 112),
(6, 3, 'Anyone else notice how many apps still store plaintext passwords in 2024? Did a quick audit of some open source projects and... yikes. Stay safe out there.', NULL, 203),
(7, 1, 'Platform update: we have now crossed 1,000 registered users! Thank you all for believing in what we are building here. More features dropping soon 🔥', NULL, 76),
(8, 2, 'Stack: Next.js → no wait, Nuxt → no wait, SvelteKit → actually just vanilla PHP is fine lol. Framework fatigue is real.', NULL, 144),
(9, 4, 'New project in the works. Cannot say much yet but it involves generative AI + photography. Very excited about this one 🤫', NULL, 38),
(10, 3, 'Remember: security through obscurity is NOT security. If your only defense is "hope attackers do not find this endpoint"... you are going to have a bad time.', NULL, 167);

INSERT OR IGNORE INTO comments (id, post_id, user_id, content) VALUES
(1, 1, 2, 'So hyped to be part of this from the early days! The vision is 🔥'),
(2, 1, 3, 'Congrats on the launch! Cannot wait to see where this goes.'),
(3, 1, 4, 'Love the aesthetic. Finally a platform that does not feel corporate.'),
(4, 2, 3, 'Classic dev behavior. We all live here lol'),
(5, 2, 4, 'The 2am deploy is a rite of passage 😂'),
(6, 3, 1, 'You are not wrong unfortunately. The state of auth in most apps is terrifying.'),
(7, 3, 2, 'I did a code review last week that made me want to cry. No validation, no sanitization, nothing.'),
(8, 5, 2, 'This is gorgeous!! What camera do you shoot with?'),
(9, 5, 3, 'Going outside is underrated. 10/10 recommend.'),
(10, 6, 1, 'We take security seriously here at VulnSphere. All passwords are properly hashed 😅'),
(11, 8, 4, 'Vanilla PHP is genuinely underrated as a punchline. Just get the job done.'),
(12, 10, 2, 'Preach!! Tell that to everyone who still puts /admin behind a URL and calls it "hidden" lol');
