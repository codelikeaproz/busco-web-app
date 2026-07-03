import { NextRequest, NextResponse } from "next/server";

const API_BASE =
  process.env.API_URL ??
  process.env.NEXT_PUBLIC_API_URL ??
  "http://127.0.0.1:8000";

async function forward(request: NextRequest, params: Promise<{ path: string[] }>) {
  const { path } = await params;
  const base = API_BASE.replace(/\/$/, "");
  const target = new URL(`${base}/${path.join("/")}`);
  target.search = request.nextUrl.search;

  try {
    const response = await fetch(target, {
      method: request.method,
      headers: {
        Accept: request.headers.get("accept") ?? "application/json",
      },
      cache: "no-store",
    });

    return new NextResponse(response.body, {
      status: response.status,
      headers: {
        "content-type": response.headers.get("content-type") ?? "application/json",
      },
    });
  } catch {
    return NextResponse.json(
      {
        message:
          "Unable to reach the Laravel API. Start `php artisan serve --host=127.0.0.1 --port=8000` and confirm the API URL is correct.",
      },
      { status: 502 },
    );
  }
}

export async function GET(
  request: NextRequest,
  context: { params: Promise<{ path: string[] }> },
) {
  return forward(request, context.params);
}
