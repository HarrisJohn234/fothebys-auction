<x-app-layout>

    <div class="p-6 max-w-xl space-y-4">
        @if (session('success'))
            <div class="text-sm text-green-700">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="text-sm text-red-700">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div>
            <h2 class="font-semibold text-xl">Preferences</h2>
        </div>

        <form method="POST" action="{{ route('client.preferences.update') }}" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm mb-1">Preferred contact method</label>
                <select name="preferred_contact_method" class="border rounded p-2 w-full">
                    <option value="email" @selected(old('preferred_contact_method', $preference->preferred_contact_method)==='email')>Email</option>
                    <option value="sms" @selected(old('preferred_contact_method', $preference->preferred_contact_method)==='sms')>SMS</option>
                    <option value="letter" @selected(old('preferred_contact_method', $preference->preferred_contact_method)==='letter')>Letter</option>
                </select>
            </div>

            <label class="flex items-center gap-2">
                <input type="checkbox" name="marketing_opt_in" value="1" @checked(old('marketing_opt_in', $preference->marketing_opt_in))>
                <span class="text-sm">Opt in to marketing updates (catalogue highlights, upcoming auctions)</span>
            </label>

            <div class="flex gap-2">
                <button class="bg-black text-white rounded px-4 py-2">Save</button>
                <a class="border rounded px-4 py-2" href="{{ route('client.dashboard') }}">Back</a>
            </div>
        </form>
    </div>
</x-app-layout>
