# Skill selection

Reviewed on 2026-09-12. The community `omeka-s` skill in
[szweibel/claude-skills](https://github.com/szweibel/claude-skills/tree/main/omeka-s)
was not installed: it assumes named Docker containers, recommends direct edits to compiled CSS
in one workflow, and includes broad SQL updates to resource visibility and site membership.
Those are unsuitable defaults for this repository. No official Omeka-maintained Agent Skills
collection was identified in this review. Use the [Omeka S developer documentation](https://omeka.org/s/docs/developer/)
and the supported core version for API contracts; local skills describe this project's behavior.

`github-actions-hardening` was installed with `gh skills` from `github/awesome-copilot`.
It applies to the existing CI and the new skill updater. Keep the upstream copy unchanged;
the repository's version-tag policy overrides its SHA-pinning recommendation.
License: `../licenses/github-awesome-copilot-MIT.txt`.

The guidance separates always-needed repository constraints from task-triggered procedures,
following [OpenAI's skills and prompts guidance](https://developers.openai.com/blog/rethinking-skills-and-prompts-for-gpt-6-astra),
[Anthropic's prompting guidance](https://platform.claude.com/docs/en/build-with-claude/prompt-engineering/prompting-claude-opus-5),
[context engineering](https://www.anthropic.com/engineering/effective-context-engineering-for-ai-agents),
and [Agent Skills](https://www.anthropic.com/engineering/equipping-agents-for-the-real-world-with-agent-skills).
