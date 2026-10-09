<?php

namespace Database\Seeders;

use Carbon\CarbonImmutable;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use RuntimeException;

class BxArticlesTableSeeder extends Seeder
{
    public function run(): void
    {
        if (!Schema::hasTable('bx_articles')) {
            throw new RuntimeException('Cannot seed articles: the bx_articles table does not exist. Run the database migrations first.');
        }

        if (!Schema::hasTable('bx_author')) {
            throw new RuntimeException('Cannot seed articles: the bx_author table does not exist. Run the database migrations first.');
        }

        DB::transaction(function (): void {
            $authorId = $this->authorId();
            $now = CarbonImmutable::now();

            foreach ($this->articles() as $index => $article) {
                $publishedAt = $now->subDays($index + 1);
                $values = [
                    'short_desc' => $article['short_desc'],
                    'article_content' => $article['article_content'],
                    'author_id' => $authorId,
                    'image_path' => '',
                    'listing_image_path' => '',
                    'article_tags' => $article['article_tags'],
                    'article_status' => 1,
                    'seo_title' => $article['article_title'],
                    'seo_keywords' => $article['seo_keywords'],
                    'seo_desc' => $article['seo_desc'],
                    'article_views' => 0,
                    'article_comments' => 0,
                    'created_by' => 1,
                    'created_at' => $publishedAt,
                    'updated_at' => $publishedAt,
                ];

                DB::table('bx_articles')->updateOrInsert(
                    ['article_title' => $article['article_title']],
                    $values
                );
            }
        });
    }

    private function authorId(): int
    {
        $author = DB::table('bx_author')
            ->orderByDesc('is_active')
            ->orderBy('author_id')
            ->first();

        if ($author) {
            return (int) $author->author_id;
        }

        $now = CarbonImmutable::now();

        return (int) DB::table('bx_author')->insertGetId([
            'author_name' => 'BusinessX Editorial',
            'author_email' => 'editorial@example.test',
            'author_desig' => 'Editorial Team',
            'author_dept' => 'Editorial',
            'is_active' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ], 'author_id');
    }

    private function articles(): array
    {
        return [
            [
                'article_title' => 'How UK SMEs Can Prepare for Digital Export Growth',
                'short_desc' => 'A practical starting point for small businesses planning to sell online across borders.',
                'article_tags' => 'business, exports, digital trade',
                'seo_keywords' => 'UK SMEs, digital exports, international ecommerce',
                'seo_desc' => 'Learn how UK small businesses can prepare their products, payments, logistics and support for digital export growth.',
                'article_content' => '<p>Digital channels make international markets more accessible to UK small businesses, but a successful launch takes more than translating a website. Start by confirming demand in a small number of target markets and checking whether your product meets local rules.</p><h2>Prepare the customer journey</h2><p>Show prices and delivery expectations clearly, offer trusted payment methods, and explain how customers can contact you after a sale. Test the entire checkout and returns process before investing in a wider launch.</p><h2>Plan fulfilment and compliance</h2><p>Compare shipping partners, duties, tax obligations and consumer protection requirements. A focused pilot helps reveal operational issues while keeping the initial commitment manageable.</p>',
            ],
            [
                'article_title' => 'Building Resilient Supply Chains for International Growth',
                'short_desc' => 'Ways growing companies can reduce disruption risk while expanding supplier and customer networks.',
                'article_tags' => 'supply chain, global trade, resilience',
                'seo_keywords' => 'supply chain resilience, international trade, business continuity',
                'seo_desc' => 'Explore practical supplier mapping, contingency planning and communication steps for more resilient cross-border supply chains.',
                'article_content' => '<p>International growth can increase both opportunity and exposure to disruption. A resilient supply chain starts with visibility: understand where critical materials come from, which routes they travel, and how long alternatives would take.</p><h2>Map dependencies and alternatives</h2><p>Rank suppliers by business impact and identify viable substitutes for the most critical inputs. Agree in advance how quality checks, lead times and escalation will work if a supplier or route becomes unavailable.</p><h2>Review plans regularly</h2><p>Use a simple continuity plan with named owners and clear communication steps. Revisit it when volumes, markets or regulations change.</p>',
            ],
            [
                'article_title' => 'Funding Options for Early-Stage UK Startups',
                'short_desc' => 'An overview of funding routes founders can compare against their growth plans and ownership goals.',
                'article_tags' => 'startup, funding, investment',
                'seo_keywords' => 'startup funding UK, angel investment, business finance',
                'seo_desc' => 'Compare common startup funding options and prepare for investor conversations with a clear financial plan.',
                'article_content' => '<p>There is no single funding route suited to every startup. Founders can compare personal capital, grants, lending, angel investment and venture capital by considering cost, speed, risk and the effect on ownership.</p><h2>Match capital to milestones</h2><p>Define what the funding will achieve and how progress will be measured. A clear use of funds helps lenders and investors assess whether the amount and timing are realistic.</p><h2>Prepare reliable information</h2><p>Keep forecasts, assumptions and customer evidence consistent. Get professional advice on legal terms before signing financing agreements.</p>',
            ],
            [
                'article_title' => 'Choosing the Right Market for Your First Export',
                'short_desc' => 'A structured way to compare international markets before committing time and budget.',
                'article_tags' => 'exports, market research, international markets',
                'seo_keywords' => 'first export market, market selection, UK exporters',
                'seo_desc' => 'Use customer demand, regulations, competition and delivery costs to shortlist your first export markets.',
                'article_content' => '<p>The most attractive export destination on paper may not be the best first market. A useful shortlist weighs customer demand alongside practical questions such as product rules, language, competition and the cost of serving customers.</p><h2>Build a comparable shortlist</h2><p>Apply the same criteria to each candidate market and record the evidence behind each score. Speak with prospective customers and local partners to test assumptions that desk research cannot confirm.</p><h2>Start with a manageable pilot</h2><p>Choose a market where you can learn quickly, fulfil orders reliably and respond to customer feedback before expanding further.</p>',
            ],
            [
                'article_title' => 'A Practical Guide to Cross-Border E-Commerce',
                'short_desc' => 'The essential decisions around payments, delivery, returns and customer service for online sellers.',
                'article_tags' => 'e-commerce, digital trade, exports',
                'seo_keywords' => 'cross-border ecommerce, international online sales, ecommerce logistics',
                'seo_desc' => 'Plan the customer experience, payments, shipping and returns needed to sell products internationally online.',
                'article_content' => '<p>Cross-border e-commerce brings a new audience within reach, while adding decisions that domestic sellers may not face. Customers need transparent prices, delivery estimates and clear information about duties before they place an order.</p><h2>Make checkout trustworthy</h2><p>Offer payment methods familiar to your target customers and test checkout on mobile devices. Explain how personal information is handled and where support is available.</p><h2>Design fulfilment around returns</h2><p>Compare carrier coverage, tracking and reverse-logistics costs. A straightforward returns policy can build confidence and prevent avoidable disputes.</p>',
            ],
            [
                'article_title' => 'How Investors Evaluate High-Growth Businesses',
                'short_desc' => 'What investors may look for in a company’s market, traction, team and financial plan.',
                'article_tags' => 'investment, business growth, startups',
                'seo_keywords' => 'investor evaluation, startup traction, business investment',
                'seo_desc' => 'Understand common investor questions about market size, customer traction, team capability and use of funds.',
                'article_content' => '<p>Investors assess more than a compelling pitch. They want to understand the customer problem, the market opportunity, evidence of demand and the team’s ability to execute.</p><h2>Show evidence and assumptions</h2><p>Present customer retention, revenue quality and acquisition costs where available. Clearly distinguish verified results from forecasts and explain the assumptions behind projections.</p><h2>Explain the next milestone</h2><p>Connect the funding request to measurable outcomes. A credible plan makes it easier to assess how additional capital could change the company’s trajectory.</p>',
            ],
            [
                'article_title' => 'Turning Sustainability into a Competitive Advantage',
                'short_desc' => 'How businesses can connect credible sustainability improvements with customer and operational value.',
                'article_tags' => 'sustainability, business growth, supply chain',
                'seo_keywords' => 'sustainable business, responsible sourcing, competitive advantage',
                'seo_desc' => 'Identify measurable sustainability improvements that support operational resilience and customer trust.',
                'article_content' => '<p>Sustainability can support long-term competitiveness when it is built into decisions rather than treated as a marketing claim. Start with the parts of your operations and supply chain where the business has the strongest influence.</p><h2>Choose measurable priorities</h2><p>Set a baseline, select achievable targets and assign ownership. Energy use, packaging, waste and supplier standards can all offer practical starting points depending on the business.</p><h2>Communicate accurately</h2><p>Use evidence for public claims and describe progress honestly. Transparent reporting helps customers and partners understand what has changed and what remains in progress.</p>',
            ],
            [
                'article_title' => 'Scaling a Startup Without Losing Customer Focus',
                'short_desc' => 'Systems and feedback habits that help a growing team maintain a consistent customer experience.',
                'article_tags' => 'startup, scaling, customer experience',
                'seo_keywords' => 'startup scaling, customer experience, business operations',
                'seo_desc' => 'Build repeatable operations while preserving customer feedback and quality as your startup grows.',
                'article_content' => '<p>As a startup grows, informal ways of working can become difficult to sustain. The goal is not to add process for its own sake, but to make reliable outcomes repeatable for customers and staff.</p><h2>Document the important work</h2><p>Identify tasks that frequently cause delays or inconsistent service. Agree on a simple standard, make responsibilities clear and give the team a way to flag exceptions.</p><h2>Keep feedback close</h2><p>Review customer questions, complaints and retention signals regularly. Use those insights to improve the process as volume increases.</p>',
            ],
            [
                'article_title' => 'Building Strong Partnerships in New Markets',
                'short_desc' => 'A due-diligence and communication checklist for businesses considering local partners.',
                'article_tags' => 'partnerships, international markets, business growth',
                'seo_keywords' => 'international business partners, market entry, partnership due diligence',
                'seo_desc' => 'Assess prospective local partners, align expectations and create a practical plan for working together.',
                'article_content' => '<p>A local partner can provide market knowledge, relationships and operational support, but a good fit requires careful assessment. Define the capabilities you need before selecting a prospective distributor, adviser or joint venture partner.</p><h2>Check fit and credibility</h2><p>Review relevant experience, references, financial standing and any potential conflicts. Where practical, speak with existing customers and visit the partner’s operation.</p><h2>Agree how you will work together</h2><p>Set expectations for responsibilities, reporting, customer ownership and problem resolution. Document the arrangement with professional legal advice suited to the market.</p>',
            ],
            [
                'article_title' => 'Technology Trends Reshaping International Trade',
                'short_desc' => 'A measured look at digital tools that can improve trade operations and decision-making.',
                'article_tags' => 'technology, global trade, digital transformation',
                'seo_keywords' => 'trade technology, digital trade, business automation',
                'seo_desc' => 'Explore how data, automation and digital documentation can support more efficient international trade operations.',
                'article_content' => '<p>Digital tools are changing how businesses manage trade documentation, monitor shipments and understand demand. The best opportunities often come from improving a specific process rather than adopting technology without a clear business need.</p><h2>Start with a defined problem</h2><p>Map the current workflow and identify where delays, manual re-entry or missing information occur. Check whether a system integrates with the partners and tools already in use.</p><h2>Measure results and manage risk</h2><p>Test changes with a limited group, protect sensitive business information and compare outcomes against a baseline before scaling up.</p>',
            ],
        ];
    }
}
